<?php

namespace App\Http\Controllers;

use App\Models\Cohort;
use App\Models\CohortMaterial;
use App\Models\CohortSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CohortMaterialController extends Controller
{
    public function show(Cohort $cohort)
    {
        $user = Auth::user();

        // Ensure student is enrolled
        if (!$user->cohortEnrollments()->where('cohort_id', $cohort->id)->exists()) {
            return redirect()->route('dashboard')->with('error', 'You are not enrolled in this cohort.');
        }

        $materials = $cohort->materials()
            ->where('type', 'material')
            ->latest()
            ->get();

        $assignments = $cohort->materials()
            ->where('type', 'assignment')
            ->latest()
            ->get();

        return view('cohorts.show', compact('cohort', 'materials', 'assignments'));
    }

    public function submit(Request $request, Cohort $cohort, CohortMaterial $material)
    {
        $user = Auth::user();

        if (!$user->cohortEnrollments()->where('cohort_id', $cohort->id)->exists()) {
            return redirect()->route('dashboard')->with('error', 'You are not enrolled in this cohort.');
        }

        if (!$material->isAssignment()) {
            return back()->with('error', 'This is not an assignment.');
        }

        // Check if already submitted
        $existing = $material->submissionBy($user->id);
        if ($existing) {
            return back()->with('error', 'You have already submitted this assignment.');
        }

        $request->validate([
            'file' => 'required|file|max:20480', // 20MB
            'notes' => 'nullable|string|max:1000',
        ]);

        $file = $request->file('file');
        $path = $file->store("cohorts/{$cohort->id}/submissions/{$user->id}", 'public');

        CohortSubmission::create([
            'cohort_material_id' => $material->id,
            'user_id' => $user->id,
            'file_path' => $path,
            'file_name' => $file->getClientOriginalName(),
            'notes' => $request->notes,
        ]);

        return back()->with('success', 'Assignment submitted successfully!');
    }
}
