<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MembershipPlan;
use App\Notifications\CreatorItemApproved;
use App\Notifications\CreatorItemRejected;
use Illuminate\Http\Request;

class MembershipController extends Controller
{
    public function index()
    {
        $memberships = MembershipPlan::with('creator')
            ->withCount('activeSubscriptions')
            ->latest()
            ->paginate(10);

        return view('admin.memberships.index', compact('memberships'));
    }

    public function show(MembershipPlan $membership)
    {
        $membership->load('creator');
        $subscribers = $membership->subscriptions()
            ->with('user')
            ->latest()
            ->paginate(20);

        return view('admin.memberships.show', compact('membership', 'subscribers'));
    }

    public function approve(MembershipPlan $membership)
    {
        $membership->update([
            'approval_status' => 'approved',
            'rejection_reason' => null,
        ]);

        if ($membership->creator && $membership->creator->isCreator()) {
            $membership->creator->notify(new CreatorItemApproved('membership', $membership->title));
        }

        return back()->with('success', "Membership \"{$membership->title}\" has been approved.");
    }

    public function reject(Request $request, MembershipPlan $membership)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        $membership->update([
            'approval_status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
        ]);

        if ($membership->creator && $membership->creator->isCreator()) {
            $membership->creator->notify(new CreatorItemRejected('membership', $membership->title, $request->rejection_reason));
        }

        return back()->with('success', "Membership \"{$membership->title}\" has been rejected.");
    }
}
