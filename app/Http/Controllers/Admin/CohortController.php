<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cohort;
use Illuminate\Http\Request;

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
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'google_meet_link' => 'nullable|url',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:upcoming,active,completed',
            'max_students' => 'nullable|integer|min:1',
        ]);

        Cohort::create($request->only([
            'title', 'description', 'price', 'google_meet_link',
            'start_date', 'end_date', 'status', 'max_students',
        ]));

        return redirect()->route('admin.cohorts.index')->with('success', 'Cohort created successfully.');
    }

    public function edit(Cohort $cohort)
    {
        return view('admin.cohorts.edit', compact('cohort'));
    }

    public function update(Request $request, Cohort $cohort)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'google_meet_link' => 'nullable|url',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:upcoming,active,completed',
            'max_students' => 'nullable|integer|min:1',
        ]);

        $cohort->update($request->only([
            'title', 'description', 'price', 'google_meet_link',
            'start_date', 'end_date', 'status', 'max_students',
        ]));

        return redirect()->route('admin.cohorts.index')->with('success', 'Cohort updated successfully.');
    }

    public function destroy(Cohort $cohort)
    {
        if ($cohort->enrollments()->count() > 0) {
            return back()->with('error', 'Cannot delete a cohort with enrolled students.');
        }

        $cohort->delete();
        return redirect()->route('admin.cohorts.index')->with('success', 'Cohort deleted.');
    }
}
