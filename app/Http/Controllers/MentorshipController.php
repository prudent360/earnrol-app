<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MentorshipController extends Controller
{
    public function index()
    {
        return view('mentorship.index');
    }

    public function show($mentor)
    {
        return view('mentorship.index'); // placeholder until Mentor model added
    }
}
