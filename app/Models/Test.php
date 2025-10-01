<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Test extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'content',
        'status',
    ];
    protected $casts = [
        'status' => \App\Enums\User::class,
    ];
        // The casts() method was removed because the 'id' field should not be cast to string due to conflicting database definitions.
}
