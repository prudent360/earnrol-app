<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Enrollment;
use App\Models\MentorshipSession;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $enrolledCoursesCount = Enrollment::where('user_id', $user->id)->count();
        $activeCourses = Enrollment::where('user_id', $user->id)
                                    ->with('course.instructor')
                                    ->latest()
                                    ->take(3)
                                    ->get();
        
        $certificationsCount = Enrollment::where('user_id', $user->id)
                                         ->whereNotNull('completed_at')
                                         ->count();
                                         
        $mentorSessionsCount = MentorshipSession::where('user_id', $user->id)->count();
        $nextSession = MentorshipSession::where('user_id', $user->id)
                                        ->where('scheduled_at', '>', now())
                                        ->where('status', 'confirmed')
                                        ->with('mentor.user')
                                        ->first();

        return view('dashboard.index', compact(
            'enrolledCoursesCount', 
            'activeCourses', 
            'certificationsCount', 
            'mentorSessionsCount',
            'nextSession'
        ));
    }
}
