<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class JobController extends Controller
{
    public function index()
    {
        return view('jobs.index');
    }

    public function show($job)
    {
        return view('jobs.index'); // placeholder until Job model added
    }

    public function apply(Request $request, $job)
    {
        return back()->with('success', 'Application submitted! The employer will contact you soon.');
    }
}
