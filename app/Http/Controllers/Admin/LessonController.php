<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LessonController extends Controller
{
    public function store(Request $request, Chapter $chapter)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'video_url'   => 'nullable|string',
            'duration'    => 'nullable|string',
            'is_preview'  => 'nullable|boolean',
            'order'       => 'nullable|integer',
        ]);

        $data['chapter_id'] = $chapter->id;
        $data['slug']       = Str::slug($data['title']);
        $data['is_preview'] = $request->boolean('is_preview');
        $data['order']      = $data['order'] ?? ($chapter->lessons()->max('order') + 1);

        $lesson = Lesson::create($data);

        return response()->json([
            'success' => true,
            'lesson'  => $lesson,
        ]);
    }

    public function update(Request $request, Lesson $lesson)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'video_url'   => 'nullable|string',
            'duration'    => 'nullable|string',
            'is_preview'  => 'nullable|boolean',
            'order'       => 'nullable|integer',
        ]);

        $data['slug']       = Str::slug($data['title']);
        $data['is_preview'] = $request->boolean('is_preview');

        $lesson->update($data);

        return response()->json([
            'success' => true,
            'lesson'  => $lesson,
        ]);
    }

    public function destroy(Lesson $lesson)
    {
        $lesson->delete();

        return response()->json([
            'success' => true,
        ]);
    }
}
