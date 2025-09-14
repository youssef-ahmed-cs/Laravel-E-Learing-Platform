<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'ID' => $this->id,
            'Name' => $this->name,
            'Email' => $this->email,
            'Role' => $this->role,
            'Bio' => $this->bio,
            'Phone' => $this->phone,
            'Username' => $this->username,
            'Photo' => $this->avatar,
            'Created At' => $this->created_at,
            'Additional Info' => $this->profile ? [
                'Age' => $this->profile->age,
                'Address' => $this->profile->address,
                'Bio' => $this->profile->bio,
            ] : null,
        ];
    }
}
