<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\JobApplication;
use Illuminate\Http\Request;

class JobApplicationController extends Controller
{
    /**
     * Display a listing of applications for a specific job.
     */
    public function index(Job $job)
    {
        $applications = $job->applications()->with('user')->latest()->paginate(20);
        return view('admin.jobs.applications', compact('job', 'applications'));
    }

    /**
     * Display the specified application.
     */
    public function show(JobApplication $application)
    {
        $application->load(['user', 'job']);
        return view('admin.job-applications.show', compact('application'));
    }

    /**
     * Update the status of an application.
     */
    public function updateStatus(Request $request, JobApplication $application)
    {
        $request->validate([
            'status' => 'required|in:pending,reviewed,accepted,rejected',
        ]);

        $application->update(['status' => $request->status]);

        return back()->with('success', 'Application status updated to ' . ucfirst($request->status));
    }

    /**
     * Remove the specified application.
     */
    public function destroy(JobApplication $application)
    {
        $application->delete();
        return back()->with('success', 'Application removed.');
    }
}
