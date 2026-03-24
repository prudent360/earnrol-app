<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cohort;
use App\Models\CohortMaterial;
use App\Models\CohortSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CohortMaterialController extends Controller
{
    public function index(Cohort $cohort)
    {
        $materials = $cohort->materials()->with('uploader')->latest()->get();
        return view('admin.cohorts.materials.index', compact('cohort', 'materials'));
    }

    public function create(Cohort $cohort)
    {
        return view('admin.cohorts.materials.create', compact('cohort'));
    }

    public function store(Request $request, Cohort $cohort)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:material,assignment',
            'due_date' => 'nullable|date',
            'file' => 'nullable|file|max:20480', // 20MB max
        ]);

        $material = new CohortMaterial([
            'cohort_id' => $cohort->id,
            'uploaded_by' => Auth::id(),
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'type' => $data['type'],
            'due_date' => $data['due_date'] ?? null,
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $path = $file->store("cohorts/{$cohort->id}/materials", 'public');
            $material->file_path = $path;
            $material->file_name = $file->getClientOriginalName();
        }

        $material->save();

        return redirect()->route('admin.cohorts.materials.index', $cohort)
            ->with('success', ucfirst($data['type']) . ' added successfully.');
    }

    public function destroy(Cohort $cohort, CohortMaterial $material)
    {
        if ($material->file_path && Storage::disk('public')->exists($material->file_path)) {
            Storage::disk('public')->delete($material->file_path);
        }

        // Delete associated submission files
        foreach ($material->submissions as $submission) {
            if ($submission->file_path && Storage::disk('public')->exists($submission->file_path)) {
                Storage::disk('public')->delete($submission->file_path);
            }
        }

        $material->delete();

        return redirect()->route('admin.cohorts.materials.index', $cohort)
            ->with('success', 'Material deleted successfully.');
    }

    public function submissions(Cohort $cohort, CohortMaterial $material)
    {
        $submissions = $material->submissions()->with('user')->latest()->get();
        return view('admin.cohorts.materials.submissions', compact('cohort', 'material', 'submissions'));
    }

    public function grade(Request $request, Cohort $cohort, CohortSubmission $submission)
    {
        $data = $request->validate([
            'grade' => 'nullable|string|max:20',
            'feedback' => 'nullable|string',
        ]);

        $submission->update($data);

        return back()->with('success', 'Submission graded successfully.');
    }
}
