<?php

namespace App\Http\Resources;

use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Profile */
class ProfileResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this?->user->name,
            'bio' => $this->bio,
            'age' => $this->age,
            'address' => $this->address,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'photo' => $this?->user->avatar,
            'phone' => $this?->user->phone,
            'username' => $this?->user->username,
        ];
    }
}
