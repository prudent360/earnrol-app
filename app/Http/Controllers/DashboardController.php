<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Enrollment;
use App\Models\MentorshipSession;
use App\Models\ProjectEnrollment;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

        // P1 Fix: Dynamic "Projects Done" count
        $projectsDoneCount = ProjectEnrollment::where('user_id', $user->id)
                                              ->whereNotNull('completed_at')
                                              ->count();

// P1 Fix: Dynamic "Skill Progress" from course enrollments by category
        $skillProgress = $this->computeSkillProgress($user);

        // P1 Fix: Dynamic streak (consecutive days with lesson activity)
        $streakDays = $this->computeStreak($user);

        return view('dashboard.index', compact(
            'enrolledCoursesCount', 
            'activeCourses', 
            'certificationsCount', 
            'mentorSessionsCount',
            'nextSession',
            'projectsDoneCount',
            'skillProgress',
            'streakDays'
        ));
    }

    /**
     * Compute per-category skill progress based on
     * enrolled courses and completed lessons.
     */
    private function computeSkillProgress($user): array
    {
        $categories = Course::categories();
        $progress = [];

        $enrolledCourseIds = Enrollment::where('user_id', $user->id)->pluck('course_id');

        if ($enrolledCourseIds->isEmpty()) {
            // Return categories with 0% if user has no enrollments
            $colors = ['#e05a3a', '#3b82f6', '#22c55e', '#8b5cf6', '#f59e0b', '#ef4444'];
            $i = 0;
            foreach ($categories as $slug => $name) {
                $progress[] = ['name' => $name, 'pct' => 0, 'color' => $colors[$i % count($colors)]];
                $i++;
            }
            return $progress;
        }

        $colors = ['#e05a3a', '#3b82f6', '#22c55e', '#8b5cf6', '#f59e0b', '#ef4444'];
        $i = 0;

        foreach ($categories as $slug => $name) {
            $categoryCourseIds = Course::where('category', $slug)
                ->whereIn('id', $enrolledCourseIds)
                ->pluck('id');

            if ($categoryCourseIds->isEmpty()) {
                $i++;
                continue; // skip categories user isn't enrolled in
            }

            // Count total lessons and completed lessons for these courses
            $totalLessons = DB::table('lessons')
                ->join('chapters', 'lessons.chapter_id', '=', 'chapters.id')
                ->whereIn('chapters.course_id', $categoryCourseIds)
                ->count();

            $completedLessons = DB::table('lesson_user')
                ->join('lessons', 'lesson_user.lesson_id', '=', 'lessons.id')
                ->join('chapters', 'lessons.chapter_id', '=', 'chapters.id')
                ->where('lesson_user.user_id', $user->id)
                ->where('lesson_user.is_completed', true)
                ->whereIn('chapters.course_id', $categoryCourseIds)
                ->count();

            $pct = $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100) : 0;

            $progress[] = [
                'name'  => $name,
                'pct'   => $pct,
                'color' => $colors[$i % count($colors)],
            ];
            $i++;
        }

        return $progress;
    }

    /**
     * Compute the consecutive-day streak from lesson_user activity.
     */
    private function computeStreak($user): int
    {
        $dates = DB::table('lesson_user')
            ->where('user_id', $user->id)
            ->whereNotNull('last_watched_at')
            ->select(DB::raw('DATE(last_watched_at) as activity_date'))
            ->groupBy('activity_date')
            ->orderByDesc('activity_date')
            ->pluck('activity_date')
            ->map(fn($d) => \Carbon\Carbon::parse($d));

        if ($dates->isEmpty()) {
            return 0;
        }

        $streak = 0;
        $expected = now()->startOfDay();

        // If the user hasn't been active today, start from yesterday
        if (!$dates->first()->isSameDay($expected)) {
            $expected = $expected->subDay();
        }

        foreach ($dates as $date) {
            if ($date->isSameDay($expected)) {
                $streak++;
                $expected = $expected->subDay();
            } else {
                break;
            }
        }

        return $streak;
    }
}
