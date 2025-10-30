<?php

namespace App\Enums;
use Illuminate\Validation\Rules\Enum as EnumRule;

enum UserRols: string
{
    case STUDENT = 'student';
    case ADMIN = 'admin';
    case INSTRUCTOR = 'instructor';

    public static function rule(): EnumRule
    {
        return new EnumRule(self::class);
    }
}
