<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model as EloquentModel;

class Category extends EloquentModel
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'slug',
    ];


    public function courses()
    {
        return $this->hasMany(Course::class , 'category_id');
    }
}
