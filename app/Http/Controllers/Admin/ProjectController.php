<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of the projects.
     */
    public function index()
    {
        $projects = Project::with('owner')->latest()->paginate(10);
        return view('admin.projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new project.
     */
    public function create()
    {
        $users = User::all();
        return view('admin.projects.create', compact('users'));
    }

    /**
     * Store a newly created project in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'category'    => 'nullable|string|max:255',
            'points'      => 'required|integer|min:0',
            'difficulty'  => 'required|in:beginner,intermediate,advanced',
            'tags'        => 'nullable|string|max:255',
            'github_url'  => 'nullable|url',
            'live_url'    => 'nullable|url',
            'status'      => 'required|in:pending,active,completed',
            'user_id'     => 'required|exists:users,id',
        ]);

        Project::create($data);

        return redirect()->route('admin.projects.index')
            ->with('success', 'Project created successfully.');
    }

    /**
     * Show the form for editing the specified project.
     */
    public function edit(Project $project)
    {
        $users = User::all();
        return view('admin.projects.edit', compact('project', 'users'));
    }

    /**
     * Update the specified project in storage.
     */
    public function update(Request $request, Project $project)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'category'    => 'nullable|string|max:255',
            'github_url'  => 'nullable|url',
            'live_url'    => 'nullable|url',
            'status'      => 'required|in:pending,active,completed',
            'user_id'     => 'required|exists:users,id',
        ]);

        $project->update($data);

        return redirect()->route('admin.projects.index')
            ->with('success', 'Project updated successfully.');
    }

    /**
     * Remove the specified project from storage.
     */
    public function destroy(Project $project)
    {
        $project->delete();

        return redirect()->route('admin.projects.index')
            ->with('success', 'Project deleted successfully.');
    }
}
