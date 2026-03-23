<?php

namespace App\Http\Controllers;

use App\Models\Mentor;
use App\Models\MentorshipSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MentorshipController extends Controller
{
    public function index()
    {
        $mentors = Mentor::with('user')->where('is_available', true)->get();
        
        $upcomingSession = null;
        if (Auth::check()) {
            $upcomingSession = MentorshipSession::where('user_id', Auth::id())
                ->where('scheduled_at', '>', now())
                ->where('status', 'confirmed')
                ->with('mentor.user')
                ->orderBy('scheduled_at', 'asc')
                ->first();
        }

        return view('mentorship.index', compact('mentors', 'upcomingSession'));
    }

    public function show(Mentor $mentor)
    {
        return view('mentorship.show', compact('mentor'));
    }
}
