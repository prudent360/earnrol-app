<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Enrollment;
use App\Notifications\LessonCompleted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LessonController extends Controller
{
    public function show(Course $course, Lesson $lesson)
    {
        // Ensure user is enrolled, unless it's a preview lesson
        $enrollment = Enrollment::where('user_id', Auth::id())
                                ->where('course_id', $course->id)
                                ->first();

        if (!$enrollment && !$lesson->is_preview) {
            return redirect()->route('courses.show', $course)
                ->with('error', 'You must be enrolled to view this lesson.');
        }

        // Load curriculum
        $course->load(['chapters.lessons']);

        // Mark current lesson as "last watched" (update pivot table)
        Auth::user()->lessonProgress()->syncWithoutDetaching([
            $lesson->id => ['last_watched_at' => now()]
        ]);

        return view('courses.lessons.show', compact('course', 'lesson', 'enrollment'));
    }

    public function complete(Course $course, Lesson $lesson)
    {
        $user = Auth::user();
        $progress = 0;
        
        // Mark as completed in pivot
        $user->lessonProgress()->syncWithoutDetaching([
            $lesson->id => ['is_completed' => true]
        ]);
 
         // Calculate new progress for enrollment
         $enrollment = Enrollment::where('user_id', $user->id)
                                 ->where('course_id', $course->id)
                                 ->first();
         
         if ($enrollment) {
             $totalLessons = $course->lessons()->count();
             $completedLessons = $user->lessonProgress()
                                      ->wherePivot('is_completed', true)
                                      ->whereIn('lesson_id', $course->lessons()->pluck('id'))
                                      ->count();
                                      
             $progress = $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100) : 0;
            
            $enrollment->update([
                'progress' => $progress,
                'completed_at' => ($progress == 100) ? now() : $enrollment->completed_at
            ]);
            // Send lesson completion notification
            try {
                $user->notify(new LessonCompleted($lesson, $course));
            } catch (\Exception $e) {
                // Notification failure should not block lesson completion
            }
        }

        return response()->json([
            'success' => true,
            'progress' => $progress ?? 0,
            'message' => 'Lesson marked as completed!'
        ]);
    }
}
