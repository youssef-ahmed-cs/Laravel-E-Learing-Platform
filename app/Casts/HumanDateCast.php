<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class HumanDateCast implements CastsAttributes
{
    /**
     * Outbound casting disabled per this project's CastsAttributes contract (expects null return type).
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): null
    {
        return null;
    }

    /**
     * Prepare the value for storage.
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return $value;
    }
}
