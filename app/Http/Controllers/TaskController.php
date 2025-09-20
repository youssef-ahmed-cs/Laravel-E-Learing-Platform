<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\TaskManagement\TaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\JsonResponse;

class TaskController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Task::class);
        $users = Task::with('user', 'lesson')->get();
        return TaskResource::collection($users);
    }

    public function store(StoreTaskRequest $request): TaskResource
    {
        $this->authorize('create', Task::class);

        return new TaskResource(Task::create($request->validated()));
    }

    public function show(Task $task): TaskResource
    {
        $this->authorize('view', $task);

        return new TaskResource($task);
    }

    public function update(TaskRequest $request, Task $task): TaskResource
    {
        $this->authorize('update', $task);

        $task->update($request->validated());

        return new TaskResource($task);
    }

    public function destroy(Task $task): JsonResponse
    {
        $this->authorize('delete', $task);

        $task->delete();

        return response()->json();
    }

    public function restore($id): JsonResponse
    {
        $task = Task::withTrashed()->findOrFail($id);
        $this->authorize('restore', $task);

        $task->restore();

        return response()->json([
            'message' => 'Task restored successfully',
            'task' => new TaskResource($task)
        ]);
    }

    public function forceDelete($id): JsonResponse
    {
        $task = Task::withTrashed()->findOrFail($id);
        $this->authorize('forceDelete', $task);

        $task->forceDelete();

        return response()->json([
            'message' => 'Task permanently deleted'
        ]);
    }

    public function getUsersByTask(Task $task): JsonResponse
    {
        $this->authorize('viewAny', $task);
        $users = Task::with('user')->get()->groupBy('user_id');
        $result = $users->map(function ($taskGroup, $userId) {
            return [
                'user_id' => $userId,
                'name' => $taskGroup->first()->user->name ?? null,
                'tasks' => TaskResource::collection($taskGroup)
            ];
        })->values();
        return response()->json($result);
    }

//    public function getLessonsByTasks(Task $task): JsonResponse
//    {
//        $this->authorize('viewAny', Task::class);
//        $tasks = Task::with('lesson')->get()->groupBy('lesson_id');
//        $result = $tasks->map(function ($taskGroup, $lessonId) {
//            return [
//                'lesson_id' => $lessonId,
//                'tasks' => TaskResource::collection($taskGroup)
//            ];
//        })->values();
//
//        return response()->json($result);
//    }
}
