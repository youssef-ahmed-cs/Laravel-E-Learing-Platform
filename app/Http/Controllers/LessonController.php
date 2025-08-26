<?php

namespace App\Http\Controllers;

use App\Http\Requests\LessonManagement\StoreLessonRequest;
use App\Http\Requests\LessonManagement\UpdateLessonRequest;
use App\Http\Resources\LessonResource;
use App\Models\Lesson;

class LessonController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Lesson::class);
        $lessons = Lesson::with('course')->get();
        return LessonResource::collection($lessons);
    }

    public function store(StoreLessonRequest $request){
        $this->authorize('create', Lesson::class);
        $request->validated();
        $lesson = Lesson::create($request->validated());
        return response()->json([
            'message' => 'Lesson created successfully',
            'lesson' => new LessonResource($lesson)
        ]);
    }

    public function update(UpdateLessonRequest $request){
        $this->authorize('update', Lesson::class);
        $request->validated();
        $data = Lesson::update($request->validated());
        return response()->json([
            'message' => 'Lesson created successfully',
            'lesson' => new LessonResource($data)
        ]);
    }

    public function destroy(Lesson $lesson)
    {
        $this->authorize('delete', $lesson);
        $lesson->delete();
        return response()->json([
            'message' => "lesson: {$lesson->title}  deleted from {$lesson->course->title} course , successfully",
        ]);
    }

    public function show(Lesson $lesson)
    {
        $this->authorize('view', $lesson);
        return new LessonResource($lesson);
    }

    public function getCourses(Lesson $lesson)
    {
        $this->authorize('view-any', $lesson);
        $courses = $lesson->course()->get(['id', 'title', 'description','status', 'level', 'duration', 'instructor_id']);
        return response()->json([
            'lesson' => $lesson->title,
            'courses' => $courses
        ]);
    }
}
