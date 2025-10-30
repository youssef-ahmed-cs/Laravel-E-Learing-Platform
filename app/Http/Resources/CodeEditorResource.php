<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CodeEditorResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'language' => $this->language,
            'version' => $this->version,
            'stdout' => $this->stdout,
            'compile_output' => $this->compile_output,
            'run_time' => $this->run_time,
            'memory' => $this->memory,
        ];
    }
}
