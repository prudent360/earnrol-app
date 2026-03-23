<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $category   = $request->get('category');
        $categories = Course::categories();

        $query = Course::published()->with('instructor');

        if ($category && array_key_exists($category, $categories)) {
            $query->byCategory($category);
        }

        $featured = Course::published()->where('is_featured', true)->first();
        $courses  = $query->where('is_featured', false)->latest()->get();

        $enrolledIds = Auth::check()
            ? Enrollment::where('user_id', Auth::id())->pluck('course_id')->toArray()
            : [];

        return view('courses.index', compact('courses', 'featured', 'categories', 'category', 'enrolledIds'));
    }

    public function show(Course $course)
    {
        $course->load('instructor');
        $enrolled = Auth::check()
            ? Enrollment::where('user_id', Auth::id())->where('course_id', $course->id)->first()
            : null;

        return view('courses.show', compact('course', 'enrolled'));
    }

    public function enroll(Course $course)
    {
        $existing = Enrollment::where('user_id', Auth::id())
                              ->where('course_id', $course->id)
                              ->first();

        if (!$existing) {
            Enrollment::create([
                'user_id'   => Auth::id(),
                'course_id' => $course->id,
                'progress'  => 0,
            ]);
            $course->increment('student_count');
        }

        return redirect()->route('courses.show', $course)
            ->with('success', 'You are now enrolled in ' . $course->title);
    }
}
