<?php

namespace App\Http\Controllers;

use App\Models\Mentor;
use App\Models\MentorshipSession;
use App\Notifications\MentorshipSessionBooked;
use App\Notifications\NewMentorshipRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class MentorshipSessionController extends Controller
{
    /**
     * Display a listing of the user's sessions.
     */
    public function index()
    {
        $user = Auth::user();
        
        // If user is a mentor, they might have their own sessions to manage
        // For now, focusing on student view
        $upcomingSessions = MentorshipSession::where('user_id', $user->id)
            ->where('scheduled_at', '>', now())
            ->with('mentor.user')
            ->orderBy('scheduled_at', 'asc')
            ->get();

        $pastSessions = MentorshipSession::where('user_id', $user->id)
            ->where('scheduled_at', '<=', now())
            ->with('mentor.user')
            ->orderBy('scheduled_at', 'desc')
            ->get();

        return view('mentorship.sessions', compact('upcomingSessions', 'pastSessions'));
    }

    /**
     * Store a newly created session in storage.
     */
    public function store(Request $request, Mentor $mentor)
    {
        $request->validate([
            'scheduled_at' => 'required|date|after:now',
            'topic'        => 'required|string|max:255',
            'notes'        => 'nullable|string',
        ]);

        $session = MentorshipSession::create([
            'mentor_id'        => $mentor->id,
            'user_id'          => Auth::id(),
            'scheduled_at'     => $request->scheduled_at,
            'duration_minutes' => 45, // default
            'status'           => 'confirmed', // auto-confirm for now
            'topic'            => $request->topic,
            'notes'            => $request->notes,
            'meeting_link'     => 'https://meet.google.com/abc-defg-hij', // placeholder
        ]);

        // Notify student
        Auth::user()->notify(new MentorshipSessionBooked($session));

        // Notify mentor
        if ($mentor->user) {
            $mentor->user->notify(new NewMentorshipRequest($session));
        }

        return back()->with('success', 'Your session has been booked successfully!');
    }

    /**
     * Join the session link.
     */
    public function join(MentorshipSession $session)
    {
        if ($session->user_id !== Auth::id() && $session->mentor->user_id !== Auth::id()) {
            abort(403);
        }

        return redirect($session->meeting_link);
    }
}
