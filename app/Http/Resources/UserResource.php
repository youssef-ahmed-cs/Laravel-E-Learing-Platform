<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'Id' => $this->id,
            'Name' => $this->name,
            'Email' => $this->email,
            'Role' => $this->role,
            'Bio' => $this->bio,
            'Avatar' => $this->avatar,
            'Phone' => $this->phone,
            'Username' => $this->username,
            'Created At' => $this->created_at,
        ];
    }
}
