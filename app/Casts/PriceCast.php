<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class PriceCast implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return '$' . number_format($value, 2);
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return (float) str_replace(['$', ','], '', $value);
    }
}
