<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends EloquentModel
{
    use HasFactory, softDeletes;

    protected $fillable = [
        'name',
        'description',
        'slug',
    ];


    public function courses(): HasMany
    {
        return $this->hasMany(Course::class, 'category_id');
    }
}
