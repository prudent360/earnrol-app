<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CreatorApplication;
use App\Notifications\CreatorItemApproved;
use App\Notifications\CreatorItemRejected;
use Illuminate\Http\Request;

class CreatorApplicationController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'pending');

        $applications = CreatorApplication::with('user')
            ->when($status !== 'all', fn ($q) => $q->where('status', $status))
            ->latest()
            ->paginate(20);

        $counts = [
            'pending'  => CreatorApplication::where('status', 'pending')->count(),
            'approved' => CreatorApplication::where('status', 'approved')->count(),
            'rejected' => CreatorApplication::where('status', 'rejected')->count(),
        ];

        return view('admin.creator-applications.index', compact('applications', 'status', 'counts'));
    }

    public function approve(CreatorApplication $application)
    {
        $application->update([
            'status'      => 'approved',
            'reviewed_at' => now(),
        ]);

        $user = $application->user;
        $user->update([
            'is_creator'  => true,
            'active_mode' => 'creator',
        ]);

        $user->notify(new CreatorItemApproved('Creator Application', 'creator_application'));

        return back()->with('success', $user->name . ' has been approved as a creator.');
    }

    public function reject(Request $request, CreatorApplication $application)
    {
        $request->validate([
            'rejection_reason' => ['required', 'string', 'max:500'],
        ]);

        $application->update([
            'status'           => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'reviewed_at'      => now(),
        ]);

        $application->user->notify(new CreatorItemRejected('Creator Application', $request->rejection_reason, 'creator_application'));

        return back()->with('success', 'Application rejected.');
    }
}
