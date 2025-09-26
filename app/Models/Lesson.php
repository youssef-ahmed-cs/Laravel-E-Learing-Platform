<?php

namespace App\Models;

use App\Observers\LessonObserver;
use App\Policies\LessonPolicy;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[ObservedBy(LessonObserver::class)]
#[UsePolicy(LessonPolicy::class)]

class Lesson extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'course_id',
        'title',
        'content',
        'order',
        'duration',
        'is_free',
        'video_url'
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function tasks(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }
}
