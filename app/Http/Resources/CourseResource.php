<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'instructor_id' => $this->instructor_id,
            'created_at' => $this->created_at,
            'level' => $this->level,
            'status' => $this->status,
            'duration' => $this->duration,
            'category' => $this->categories?->name
        ];
    }
}
