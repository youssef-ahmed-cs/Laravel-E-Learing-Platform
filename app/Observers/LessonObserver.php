<?php

namespace App\Observers;

use App\Models\Lesson;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class LessonObserver
{
    public function creating(Lesson $lesson): void
    {
        Log::info('Creating lesson: ' . $lesson->title);
    }

    public function created(Lesson $lesson): void
    {

    }

    public function updating(Lesson $lesson): void
    {
        Log::info('Updating lesson: ' . $lesson->title);
    }

    public function updated(Lesson $lesson): void
    {
    }

    public function saving(Lesson $lesson): void
    {
    }

    public function saved(Lesson $lesson): void
    {
    }

    public function deleted(Lesson $lesson): void
    {
        Log::warning('Lesson deleted: ' . $lesson->title);
    }

    public function restored(Lesson $lesson): void
    {
    }
}
