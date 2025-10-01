<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

/** @see \App\Models\Task */
class TaskCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'total_tasks' => $this->collection->count(),
            'tasks' => $this->collection,
            'lesson' => new LessonResource($this->whenLoaded('lesson')),
        ];
    }
}
