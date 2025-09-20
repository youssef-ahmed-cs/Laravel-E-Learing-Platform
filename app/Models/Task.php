<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use  SoftDeletes, HasFactory;

    protected $fillable = [
        'title',
        'priority',
        'content',
        'dateline',
        'completed',
        'user_id',
        'lesson_id',
        'deleted_at'
    ];

    protected function casts(): array
    {
        return [
            'dateline' => 'datetime',
            'completed' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }
}
