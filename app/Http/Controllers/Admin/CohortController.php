<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cohort;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CohortController extends Controller
{
    public function index()
    {
        $cohorts = Cohort::withCount('enrollments')
            ->latest()
            ->paginate(10);

        return view('admin.cohorts.index', compact('cohorts'));
    }

    public function create()
    {
        return view('admin.cohorts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'              => 'required|string|max:255',
            'description'        => 'nullable|string',
            'price'              => 'required|numeric|min:0',
            'google_meet_link'   => 'nullable|url',
            'start_date'         => 'required|date',
            'end_date'           => 'nullable|date|after_or_equal:start_date',
            'status'             => 'required|in:upcoming,active,completed',
            'max_students'       => 'nullable|integer|min:1',
            'facilitator_name'   => 'nullable|string|max:255',
            'facilitator_bio'    => 'nullable|string',
            'facilitator_image'  => 'nullable|image|max:2048',
            'schedule'           => 'nullable|string|max:255',
            'what_you_will_learn' => 'nullable|string',
            'prerequisites'      => 'nullable|string',
            'cover_image'        => 'nullable|image|max:4096',
        ]);

        $data = $request->only([
            'title', 'description', 'price', 'google_meet_link',
            'start_date', 'end_date', 'status', 'max_students',
            'facilitator_name', 'facilitator_bio', 'schedule',
            'what_you_will_learn', 'prerequisites',
        ]);

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('cohorts', 'public');
        }

        if ($request->hasFile('facilitator_image')) {
            $data['facilitator_image'] = $request->file('facilitator_image')->store('cohorts/facilitators', 'public');
        }

        Cohort::create($data);

        return redirect()->route('admin.cohorts.index')->with('success', 'Cohort created successfully.');
    }

    public function show(Cohort $cohort)
    {
        return redirect()->route('admin.cohorts.edit', $cohort);
    }

    public function edit(Cohort $cohort)
    {
        return view('admin.cohorts.edit', compact('cohort'));
    }

    public function update(Request $request, Cohort $cohort)
    {
        $request->validate([
            'title'              => 'required|string|max:255',
            'description'        => 'nullable|string',
            'price'              => 'required|numeric|min:0',
            'google_meet_link'   => 'nullable|url',
            'start_date'         => 'required|date',
            'end_date'           => 'nullable|date|after_or_equal:start_date',
            'status'             => 'required|in:upcoming,active,completed',
            'max_students'       => 'nullable|integer|min:1',
            'facilitator_name'   => 'nullable|string|max:255',
            'facilitator_bio'    => 'nullable|string',
            'facilitator_image'  => 'nullable|image|max:2048',
            'schedule'           => 'nullable|string|max:255',
            'what_you_will_learn' => 'nullable|string',
            'prerequisites'      => 'nullable|string',
            'cover_image'        => 'nullable|image|max:4096',
        ]);

        $data = $request->only([
            'title', 'description', 'price', 'google_meet_link',
            'start_date', 'end_date', 'status', 'max_students',
            'facilitator_name', 'facilitator_bio', 'schedule',
            'what_you_will_learn', 'prerequisites',
        ]);

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

        return redirect()->route('admin.cohorts.index')->with('success', 'Cohort updated successfully.');
    }

    public function destroy(Cohort $cohort)
    {
        if ($cohort->enrollments()->count() > 0) {
            return back()->with('error', 'Cannot delete a cohort with enrolled students.');
        }

        if ($cohort->cover_image) {
            Storage::disk('public')->delete($cohort->cover_image);
        }
        if ($cohort->facilitator_image) {
            Storage::disk('public')->delete($cohort->facilitator_image);
        }

        $cohort->delete();
        return redirect()->route('admin.cohorts.index')->with('success', 'Cohort deleted.');
    }
}
