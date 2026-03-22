<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        return view('courses.index');
    }

    public function show($course)
    {
        return view('courses.index'); // placeholder until Course model added
    }
}
