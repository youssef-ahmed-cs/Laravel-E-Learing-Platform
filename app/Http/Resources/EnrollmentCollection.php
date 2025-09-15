<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

/** @see \App\Models\Enrollment */
class EnrollmentCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection->map(function ($enrollment) {
                return [
                    'id' => $enrollment->id,
                    'user' => [
                        'id' => $enrollment->user->id,
                        'name' => $enrollment->user->name,
                        'email' => $enrollment->user->email,
                    ],
                    'course' => [
                        'id' => $enrollment->course->id,
                        'title' => $enrollment->course->title,
                        'description' => $enrollment->course->description,
                        'duration' => $enrollment->course->duration ?? null,
                        'level' => $enrollment->course->level ?? null,
                    ],
                    'enrollment_date' => $enrollment->created_at->format('Y-m-d H:i:s'),
                    'status' => $enrollment->status ?? 'active',
                    'progress' => $enrollment->progress ?? 0,
                ];
            }),
            'meta' => [
                'total' => $this->collection->count(),
            ],
        ];
    }
}
