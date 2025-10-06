<?php

namespace App\Models;

use App\Casts\HumanDateCast;
use App\Casts\PriceCast;
use App\Observers\CourseObserver;
use App\Policies\CoursePolicy;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

#[ObservedBy([CourseObserver::class])]
#[UsePolicy(CoursePolicy::class)]
class Course extends Model
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'price',
        'thumbnail',
        'instructor_id',
        'level',
        'status',
        'duration',
        'category_id',
    ];

    public function casts(): array
    {
        return [
//            'price' => 'decimal:2',
            'duration' => 'integer',
            'created_at' => 'datetime',
        ];
    }

    public function instructor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function categories(): belongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
