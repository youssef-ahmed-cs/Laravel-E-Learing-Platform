<?php

namespace App\Enums;

enum User: string
{
    case ADMIN = 'admin';
    case STUDENT = 'student';
    case INSTRUCTOR = 'instructor';
}
