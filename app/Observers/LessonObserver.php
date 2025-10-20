<?php

namespace App\Observers;

use App\Models\Lesson;
use Illuminate\Support\Facades\Log;

class LessonObserver
{
    public function creating(Lesson $lesson): void
    {
        Log::info('Creating lesson: '.$lesson->title);
    }

    public function updating(Lesson $lesson): void
    {
        Log::info('Updating lesson: '.$lesson->title);
    }

    public function deleted(Lesson $lesson): void
    {
        Log::warning('Lesson deleted: '.$lesson->title);
    }
}
