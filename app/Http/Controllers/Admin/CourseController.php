<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\User;
use App\Notifications\CourseUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $query = Course::with('instructor');

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $courses    = $query->latest()->paginate(15)->withQueryString();
        $categories = Course::categories();
        $instructors = User::where('role', 'mentor')->orWhere('role', 'superadmin')->get();

        return view('admin.courses.index', compact('courses', 'categories', 'instructors'));
    }

    public function create()
    {
        $categories  = Course::categories();
        $instructors = User::whereIn('role', ['mentor', 'superadmin'])->get();

        return view('admin.courses.create', compact('categories', 'instructors'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'          => 'required|string|max:255',
            'description'    => 'nullable|string',
            'category'       => 'required|string',
            'level'          => 'required|in:beginner,intermediate,advanced',
            'price'          => 'required|numeric|min:0',
            'duration_hours' => 'required|integer|min:0',
            'lesson_count'   => 'required|integer|min:0',
            'rating'         => 'nullable|numeric|min:0|max:5',
            'student_count'  => 'nullable|integer|min:0',
            'badge'          => 'nullable|string|max:50',
            'icon_color'     => 'nullable|string|max:20',
            'instructor_id'  => 'nullable|exists:users,id',
            'status'         => 'required|in:draft,published',
            'thumbnail'      => 'nullable|image|max:2048',
        ]);

        $data['is_free']  = $data['price'] == 0;
        $data['is_featured'] = $request->boolean('is_featured');
        $data['slug']     = Str::slug($data['title']);
        $data['rating']   = $data['rating'] ?? 0;
        $data['student_count'] = $data['student_count'] ?? 0;

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('courses', 'public');
        }

        Course::create($data);

        return redirect()->route('admin.courses.index')
            ->with('success', 'Course created successfully.');
    }

    public function edit(Course $course)
    {
        $course->load(['chapters.lessons']);
        $categories  = Course::categories();
        $instructors = User::whereIn('role', ['mentor', 'superadmin'])->get();

        return view('admin.courses.edit', compact('course', 'categories', 'instructors'));
    }

    public function update(Request $request, Course $course)
    {
        $data = $request->validate([
            'title'          => 'required|string|max:255',
            'description'    => 'nullable|string',
            'category'       => 'required|string',
            'level'          => 'required|in:beginner,intermediate,advanced',
            'price'          => 'required|numeric|min:0',
            'duration_hours' => 'required|integer|min:0',
            'lesson_count'   => 'required|integer|min:0',
            'rating'         => 'nullable|numeric|min:0|max:5',
            'student_count'  => 'nullable|integer|min:0',
            'badge'          => 'nullable|string|max:50',
            'icon_color'     => 'nullable|string|max:20',
            'instructor_id'  => 'nullable|exists:users,id',
            'status'         => 'required|in:draft,published',
            'thumbnail'      => 'nullable|image|max:2048',
        ]);

        $data['is_free']     = $data['price'] == 0;
        $data['is_featured'] = $request->boolean('is_featured');
        $data['rating']      = $data['rating'] ?? 0;
        $data['student_count'] = $data['student_count'] ?? 0;

        if ($request->hasFile('thumbnail')) {
            if ($course->thumbnail) {
                Storage::disk('public')->delete($course->thumbnail);
            }
            $data['thumbnail'] = $request->file('thumbnail')->store('courses', 'public');
        }

        $course->update($data);

        // Notify enrolled students about the update
        if ($course->status === 'published') {
            $enrolledUsers = User::whereHas('enrollments', function ($q) use ($course) {
                $q->where('course_id', $course->id);
            })->get();

            Notification::send($enrolledUsers, new CourseUpdated($course));
        }

        return redirect()->route('admin.courses.index')
            ->with('success', 'Course updated successfully.');
    }

    public function destroy(Course $course)
    {
        if ($course->thumbnail) {
            Storage::disk('public')->delete($course->thumbnail);
        }
        $course->delete();

        return redirect()->route('admin.courses.index')
            ->with('success', 'Course deleted.');
    }
}
