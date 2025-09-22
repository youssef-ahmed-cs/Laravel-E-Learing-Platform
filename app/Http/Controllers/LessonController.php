<?php

namespace App\Http\Controllers;

use App\Http\Requests\LessonManagement\StoreLessonRequest;
use App\Http\Requests\LessonManagement\UpdateLessonRequest;
use App\Http\Resources\LessonResource;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class LessonController extends Controller
{
    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $this->authorize('viewAny', Lesson::class);
        $lessons = Lesson::with('course')->get();
        return LessonResource::collection($lessons);
    }

    public function store(StoreLessonRequest $request): JsonResponse
    {
        $this->authorize('create', Lesson::class);
        $request->validated();
        $lesson = Lesson::create($request->validated());
        return response()->json([
            'message' => 'Lesson created successfully',
            'lesson' => new LessonResource($lesson)
        ]);
    }

    public function update(UpdateLessonRequest $request): JsonResponse
    {
        $this->authorize('update', Lesson::class);
        $data = Lesson::update($request->validated());
        return response()->json([
            'message' => 'Lesson created successfully',
            'lesson' => new LessonResource($data)
        ]);
    }

    public function destroy(Lesson $lesson): JsonResponse
    {
        $this->authorize('delete', $lesson);
        $lesson->delete();
        return response()->json([
            'message' => "lesson: {$lesson->title}  deleted from {$lesson->course->title} course , successfully",
        ]);
    }

    public function lessonTasks(Lesson $lesson): JsonResponse
    {
        $this->authorize('view', $lesson);

        $tasks = $lesson->tasks()->get();
        return response()->json([
            'tasks' => $tasks,
            'total_tasks' => $tasks->count(),
            'lesson' => $lesson->title
        ], 200);
    }

    public function show(Lesson $lesson): LessonResource
    {
        $this->authorize('view', $lesson);
        return new LessonResource($lesson);
    }

    public function getCourses(Lesson $lesson): JsonResponse
    {
        $this->authorize('view-any', $lesson);
        $courses = $lesson->course()->get(['id', 'title', 'description', 'status', 'level', 'duration', 'instructor_id']);
        return response()->json([
            'lesson' => $lesson->title,
            'courses' => $courses
        ]);
    }

    public function restore(int $id): JsonResponse
    {
        $lesson = Lesson::onlyTrashed()->find($id);

        if (!$lesson) {
            return response()->json(['message' => 'Losson not found or not trashed.'], 404);
        }

        $this->authorize('restore', $lesson);

        $lesson->restore();

        return response()->json([
            'message' => 'Lesson restored successfully',
            'data' => new LessonResource($lesson),
        ], 200);
    }
}
