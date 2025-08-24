<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EnrollmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'User_id' => $this->user_id,
            'Course_id' => $this->course_id,
            'Enrolled_at' => $this->enrolled_at,
            'Completed_at' => $this->completed_at,
            'student name' =>$this->user?->name,
            'course name' =>$this->course?->title,
            'course price' =>$this->course?->price,
        ];
    }
}
