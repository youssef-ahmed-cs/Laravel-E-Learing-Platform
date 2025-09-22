<?php

namespace App\Observers;

use App\Models\Task;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class TaskObserver
{
    public function creating(Task $task): void
    {
        $task->content = Str::limit($task->content);
    }

    public function created(Task $task): void
    {
        Log::info('Task created: ' . $task->title . ' with ID ' . $task->id);
    }

    public function updating(Task $task): void
    {
    }

    public function updated(Task $task): void
    {
    }

    public function saving(Task $task): void
    {
    }

    public function saved(Task $task): void
    {
    }

    public function deleted(Task $task): void
    {
        Log::warning('Task deleted: ' . $task->title . ' with ID ' . $task->id);
    }

    public function restored(Task $task): void
    {
    }
}
