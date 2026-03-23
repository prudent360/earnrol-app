<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Chapter;
use Illuminate\Http\Request;

class ChapterController extends Controller
{
    public function store(Request $request, Course $course)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'order' => 'nullable|integer',
        ]);

        $data['course_id'] = $course->id;
        $data['order'] = $data['order'] ?? ($course->chapters()->max('order') + 1);

        $chapter = Chapter::create($data);

        return response()->json([
            'success' => true,
            'chapter' => $chapter,
        ]);
    }

    public function update(Request $request, Chapter $chapter)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'order' => 'nullable|integer',
        ]);

        $chapter->update($data);

        return response()->json([
            'success' => true,
            'chapter' => $chapter,
        ]);
    }

    public function destroy(Chapter $chapter)
    {
        $chapter->delete();

        return response()->json([
            'success' => true,
        ]);
    }
}
