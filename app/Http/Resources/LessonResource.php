<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LessonResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'Title' => $this->title,
            'Content' => $this->content,
            'Order' => $this->order,
            'Duration' => $this->duration,
            'IS_Free' => $this->is_free,
            'Video_url' => $this->video_url,
            'Course' => new CourseResource($this->course)
        ];
    }
}
