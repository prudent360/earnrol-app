<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $query = Project::with('owner');
        
        // Get unique categories for the filters
        $categories = Project::whereNotNull('category')->distinct()->pluck('category');
        
        // Fetch user enrollments if logged in
        $userEnrollments = (auth()->check() && method_exists(auth()->user(), 'projectEnrollments'))
            ? auth()->user()->projectEnrollments()->pluck('project_id')->toArray() 
            : [];
        
        $myProjectsCount = (auth()->check() && method_exists(auth()->user(), 'projectEnrollments'))
            ? auth()->user()->projectEnrollments()->count()
            : 0;

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->status === 'my-projects') {
            $query->whereIn('id', $userEnrollments);
        } elseif ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $projects = $query->latest()->paginate(12)->withQueryString();

        return view('projects.index', compact('projects', 'userEnrollments', 'myProjectsCount', 'categories'));
    }

    public function enroll(Project $project)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Please login to start a project.');
        }

        auth()->user()->projectEnrollments()->firstOrCreate([
            'project_id' => $project->id,
        ], [
            'status' => 'in-progress',
            'progress' => 0
        ]);

        return redirect()->route('projects.index', ['status' => 'my-projects'])
            ->with('success', 'Project started! Good luck.');
    }

    public function show(Project $project)
    {
        return view('projects.show', compact('project'));
    }
}
