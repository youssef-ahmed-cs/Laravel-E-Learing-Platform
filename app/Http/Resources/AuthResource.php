<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'Name' => $this->name,
            'Email' => $this->email,
            'Role' => $this->role,
            'Bio' => $this->bio,
            'Phone' => $this->phone,
            'Username' => $this->username,
        ];
    }
}
