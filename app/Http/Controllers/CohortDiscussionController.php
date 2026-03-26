<?php

namespace App\Http\Controllers;

use App\Models\Cohort;
use App\Models\CohortDiscussion;
use Illuminate\Http\Request;

class CohortDiscussionController extends Controller
{
    public function index(Cohort $cohort)
    {
        // Ensure user is enrolled
        $enrolled = $cohort->enrollments()->where('user_id', auth()->id())->exists();
        if (!$enrolled && !auth()->user()->isAdmin()) {
            abort(403, 'You must be enrolled in this cohort to view discussions.');
        }

        $discussions = $cohort->discussions()
            ->whereNull('parent_id')
            ->with(['user', 'replies.user'])
            ->latest()
            ->paginate(20);

        return view('cohorts.discussions', compact('cohort', 'discussions'));
    }

    public function store(Request $request, Cohort $cohort)
    {
        $enrolled = $cohort->enrollments()->where('user_id', auth()->id())->exists();
        if (!$enrolled && !auth()->user()->isAdmin()) {
            abort(403, 'You must be enrolled in this cohort to post.');
        }

        $data = $request->validate([
            'body'      => ['required', 'string', 'max:2000'],
            'parent_id' => ['nullable', 'exists:cohort_discussions,id'],
        ]);

        // If replying, ensure parent belongs to same cohort
        if ($data['parent_id'] ?? null) {
            $parent = CohortDiscussion::where('id', $data['parent_id'])
                ->where('cohort_id', $cohort->id)
                ->whereNull('parent_id') // only reply to top-level posts
                ->firstOrFail();
        }

        CohortDiscussion::create([
            'cohort_id' => $cohort->id,
            'user_id'   => auth()->id(),
            'parent_id' => $data['parent_id'] ?? null,
            'body'      => $data['body'],
        ]);

        return back()->with('success', $data['parent_id'] ?? null ? 'Reply posted.' : 'Discussion posted.');
    }

    public function destroy(Cohort $cohort, CohortDiscussion $discussion)
    {
        // Only author or admin can delete
        if ($discussion->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        if ($discussion->cohort_id !== $cohort->id) {
            abort(404);
        }

        $discussion->delete();

        return back()->with('success', 'Post deleted.');
    }
}
