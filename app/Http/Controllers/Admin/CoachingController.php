<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CoachingService;
use App\Notifications\CreatorItemApproved;
use App\Notifications\CreatorItemRejected;
use Illuminate\Http\Request;

class CoachingController extends Controller
{
    public function index()
    {
        $services = CoachingService::with('creator')
            ->withCount('bookings')
            ->latest()
            ->paginate(10);

        return view('admin.coaching.index', compact('services'));
    }

    public function approve(CoachingService $coaching)
    {
        $coaching->update([
            'approval_status' => 'approved',
            'rejection_reason' => null,
        ]);

        if ($coaching->creator && $coaching->creator->isCreator()) {
            $coaching->creator->notify(new CreatorItemApproved('coaching service', $coaching->title));
        }

        return back()->with('success', "Coaching service \"{$coaching->title}\" has been approved.");
    }

    public function reject(Request $request, CoachingService $coaching)
    {
        $request->validate(['rejection_reason' => 'required|string|max:1000']);

        $coaching->update([
            'approval_status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
        ]);

        if ($coaching->creator && $coaching->creator->isCreator()) {
            $coaching->creator->notify(new CreatorItemRejected('coaching service', $coaching->title, $request->rejection_reason));
        }

        return back()->with('success', "Coaching service \"{$coaching->title}\" has been rejected.");
    }
}
