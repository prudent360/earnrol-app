<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\User;
use Illuminate\Http\Request;

class JobController extends Controller
{
    /**
     * Display a listing of the jobs.
     */
    public function index()
    {
        $jobs = Job::with('poster')->latest()->paginate(10);
        return view('admin.jobs.index', compact('jobs'));
    }

    /**
     * Show the form for creating a new job.
     */
    public function create()
    {
        $employers = User::whereIn('role', ['employer', 'admin', 'superadmin'])->get();
        return view('admin.jobs.create', compact('employers'));
    }

    /**
     * Store a newly created job in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title'        => 'required|string|max:255',
            'company'      => 'required|string|max:255',
            'location'     => 'nullable|string|max:255',
            'type'         => 'required|in:full-time,part-time,contract,internship',
            'salary_range' => 'nullable|string|max:100',
            'description'  => 'nullable|string',
            'requirements' => 'nullable|string',
            'status'       => 'required|in:active,closed',
            'user_id'      => 'required|exists:users,id',
        ]);

        Job::create($data);

        return redirect()->route('admin.jobs.index')
            ->with('success', 'Job posted successfully.');
    }

    /**
     * Show the form for editing the specified job.
     */
    public function edit(Job $job)
    {
        $employers = User::whereIn('role', ['employer', 'admin', 'superadmin'])->get();
        return view('admin.jobs.edit', compact('job', 'employers'));
    }

    /**
     * Update the specified job in storage.
     */
    public function update(Request $request, Job $job)
    {
        $data = $request->validate([
            'title'        => 'required|string|max:255',
            'company'      => 'required|string|max:255',
            'location'     => 'nullable|string|max:255',
            'type'         => 'required|in:full-time,part-time,contract,internship',
            'salary_range' => 'nullable|string|max:100',
            'description'  => 'nullable|string',
            'requirements' => 'nullable|string',
            'status'       => 'required|in:active,closed',
            'user_id'      => 'required|exists:users,id',
        ]);

        $job->update($data);

        return redirect()->route('admin.jobs.index')
            ->with('success', 'Job updated successfully.');
    }

    /**
     * Remove the specified job from storage.
     */
    public function destroy(Job $job)
    {
        $job->delete();

        return redirect()->route('admin.jobs.index')
            ->with('success', 'Job deleted successfully.');
    }
}
