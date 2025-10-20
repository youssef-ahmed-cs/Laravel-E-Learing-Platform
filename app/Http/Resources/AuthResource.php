<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class AuthResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'username' => $this->username,
            'bio' => $this->bio,
            'phone' => $this->phone,
            // Provide a resolvable public URL to the avatar if a path is stored
            'avatar_url' => $this->avatar ? Storage::url($this->avatar) : null,
            'email_verified_at' => $this->email_verified_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'id' => $this->id,
            'is_premium' => $this->is_premium,
        ];
    }
}
