<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'Instructor' => [
                'Name' => $this->instructor?->name,
                'Email' => $this->instructor?->email,
            ],
            'created_at' => $this->created_at,
            'level' => $this->level,
            'price' => $this->price,
            'status' => $this->status,
            'thumbnail' => $this->thumbnail ? asset('storage/' . $this->thumbnail) : null,
            'duration' => $this->duration,
                        'number_of_enrollments' => $this->enrollments()->count(),
            'category' => $this->categories?->name,
            'lessons' => [
                'total' => $this->lessons->count(),
                'items' => $this->whenLoaded('lessons', function () {
                    return $this->lessons->map(function ($lesson) {
                        return [
                            'id' => $lesson->id,
                            'title' => $lesson->title,
                            'duration' => $lesson->duration,
                        ];
                    });
                }),
            ],
        ];
    }
}
