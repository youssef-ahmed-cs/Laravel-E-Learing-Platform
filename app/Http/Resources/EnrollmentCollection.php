<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class EnrollmentCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'enrollments' => $this->collection->map(function ($enrollment) {
                return [
                    'id' => $enrollment->id,
                    'user' => [
                        'id' => $enrollment->user->id,
                        'name' => $enrollment->user->name,
                        'email' => $enrollment->user->email,
                        'avatar' => $enrollment->user->avatar_url ?? null,
                    ],
                    'course' => [
                        'id' => $enrollment->course->id,
                        'title' => $enrollment->course->title,
                        'slug' => $enrollment->course->slug ?? null,
                        'description' => $enrollment->course->description,
                        'thumbnail' => $enrollment->course->thumbnail_url ?? null,
                        'duration' => $enrollment->course->duration,
                        'level' => $enrollment->course->level,
                        'instructor' => $enrollment->course->instructor->name ?? null,
                    ],
                    'enrollment_date' => $enrollment->created_at->format('Y-m-d'),
                    'enrollment_datetime' => $enrollment->created_at,
                    'status' => $enrollment->status ?? 'active',
                    'progress_percentage' => (int) ($enrollment->progress ?? 0),
                    'completed_at' => $enrollment->completed_at,
                    'certificate_url' => $enrollment->certificate_url ?? null,
                ];
            }),
            'summary' => [
                'total_enrollments' => $this->collection->count(),
                'active_enrollments' => $this->collection->where('status', 'active')->count(),
                'completed_enrollments' => $this->collection->where('status', 'completed')->count(),
                'average_progress' => round($this->collection->avg('progress') ?? 0, 2),
            ],
        ];
    }
}
