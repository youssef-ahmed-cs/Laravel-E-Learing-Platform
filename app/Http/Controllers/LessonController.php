<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\LessonManagement\{StoreLessonRequest, UpdateLessonRequest};
use App\Http\Resources\LessonResource;
use App\Models\Lesson;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class LessonController extends Controller
{
    public function index(): AnonymousResourceCollection
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
            'message' => "lesson: $lesson->title  deleted from {$lesson->course->title} course , successfully",
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
        ]);
    }

    public function show(Lesson $lesson): JsonResponse|LessonResource
    {
        try {
            $this->authorize('view', $lesson);
            return new LessonResource($lesson);
        } catch (ModelNotFoundException $e) {
            Log::error('Error showing lesson: ' . $e->getMessage());
            return response()->json(['message' => 'Unable to retrieve lesson.'], 500);
        }
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
            return response()->json(['message' => 'Lesson not found or not trashed.'], 404);
        }

        $this->authorize('restore', $lesson);

        $lesson->restore();

        return response()->json([
            'message' => 'Lesson restored successfully',
            'data' => new LessonResource($lesson),
        ]);
    }
}
