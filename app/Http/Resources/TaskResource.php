<?php

namespace App\Http\Resources;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Task */
class TaskResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'Id' => $this->id,
            'Title' => $this->title,
            'Priority' => $this->priority,
            'Content' => $this->content,
            'Dateline' => $this->dateline->format('Y-m-d H:i:s'),
            'Completed' => $this->completed,
            'Created At' => $this->created_at->format('Y-m-d H:i:s'),
            'Updated At' => $this->updated_at->format('Y-m-d H:i:s'),
            'Lesson' => new LessonResource($this->whenLoaded('lesson')),
        ];
    }
}
