<?php

namespace App\Http\Controllers\Creator;

use App\Http\Controllers\Controller;
use App\Models\Cohort;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CohortController extends Controller
{
    public function index()
    {
        $cohorts = Auth::user()->createdCohorts()
            ->withCount('enrollments')
            ->latest()
            ->paginate(10);

        return view('creator.cohorts.index', compact('cohorts'));
    }

    public function create()
    {
        return view('creator.cohorts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'            => 'required|string|max:255',
            'description'      => 'nullable|string',
            'price'            => 'required|numeric|min:0',
            'start_date'       => 'required|date',
            'end_date'         => 'nullable|date|after_or_equal:start_date',
            'max_students'     => 'nullable|integer|min:1',
            'google_meet_link' => 'nullable|url',
            'schedule'         => 'nullable|string',
            'what_you_will_learn' => 'nullable|string',
            'prerequisites'    => 'nullable|string',
            'facilitator_name' => 'nullable|string|max:255',
            'facilitator_bio'  => 'nullable|string',
            'facilitator_image' => 'nullable|image|max:2048',
            'cover_image'      => 'nullable|image|max:4096',
        ]);

        $data = $request->only([
            'title', 'description', 'price', 'start_date', 'end_date',
            'max_students', 'google_meet_link', 'schedule',
            'what_you_will_learn', 'prerequisites',
            'facilitator_name', 'facilitator_bio',
        ]);

        $data['creator_id'] = Auth::id();
        $data['status'] = 'upcoming';
        $data['approval_status'] = 'pending';

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('cohorts', 'public');
        }

        if ($request->hasFile('facilitator_image')) {
            $data['facilitator_image'] = $request->file('facilitator_image')->store('cohorts/facilitators', 'public');
        }

        Cohort::create($data);

        return redirect()->route('creator.cohorts.index')
            ->with('success', 'Cohort submitted for review! It will be visible once approved by an admin.');
    }

    public function edit(Cohort $cohort)
    {
        $this->authorizeOwner($cohort);

        return view('creator.cohorts.edit', compact('cohort'));
    }

    public function update(Request $request, Cohort $cohort)
    {
        $this->authorizeOwner($cohort);

        $request->validate([
            'title'            => 'required|string|max:255',
            'description'      => 'nullable|string',
            'price'            => 'required|numeric|min:0',
            'start_date'       => 'required|date',
            'end_date'         => 'nullable|date|after_or_equal:start_date',
            'max_students'     => 'nullable|integer|min:1',
            'google_meet_link' => 'nullable|url',
            'schedule'         => 'nullable|string',
            'what_you_will_learn' => 'nullable|string',
            'prerequisites'    => 'nullable|string',
            'facilitator_name' => 'nullable|string|max:255',
            'facilitator_bio'  => 'nullable|string',
            'facilitator_image' => 'nullable|image|max:2048',
            'cover_image'      => 'nullable|image|max:4096',
        ]);

        $data = $request->only([
            'title', 'description', 'price', 'start_date', 'end_date',
            'max_students', 'google_meet_link', 'schedule',
            'what_you_will_learn', 'prerequisites',
            'facilitator_name', 'facilitator_bio',
        ]);

        // Re-review if previously approved
        if ($cohort->approval_status === 'approved') {
            $data['approval_status'] = 'pending';
            $data['rejection_reason'] = null;
        }

        if ($request->hasFile('cover_image')) {
            if ($cohort->cover_image) {
                Storage::disk('public')->delete($cohort->cover_image);
            }
            $data['cover_image'] = $request->file('cover_image')->store('cohorts', 'public');
        }

        if ($request->hasFile('facilitator_image')) {
            if ($cohort->facilitator_image) {
                Storage::disk('public')->delete($cohort->facilitator_image);
            }
            $data['facilitator_image'] = $request->file('facilitator_image')->store('cohorts/facilitators', 'public');
        }

        $cohort->update($data);

        return redirect()->route('creator.cohorts.index')
            ->with('success', 'Cohort updated.' . ($cohort->approval_status === 'pending' ? ' It will be re-reviewed by an admin.' : ''));
    }

    public function destroy(Cohort $cohort)
    {
        $this->authorizeOwner($cohort);

        if ($cohort->enrollments()->exists()) {
            return back()->with('error', 'Cannot delete a cohort that has enrollments.');
        }

        if ($cohort->cover_image) {
            Storage::disk('public')->delete($cohort->cover_image);
        }
        if ($cohort->facilitator_image) {
            Storage::disk('public')->delete($cohort->facilitator_image);
        }

        $cohort->delete();

        return redirect()->route('creator.cohorts.index')
            ->with('success', 'Cohort deleted.');
    }

    protected function authorizeOwner(Cohort $cohort): void
    {
        if ($cohort->creator_id !== Auth::id()) {
            abort(403, 'You do not own this cohort.');
        }
    }
}
