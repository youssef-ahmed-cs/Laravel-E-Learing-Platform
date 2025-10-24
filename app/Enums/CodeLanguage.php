<?php

namespace App\Enums;

use Illuminate\Validation\Rules\Enum as EnumRule;

enum CodeLanguage: string
{
    case PHP = 'php';
    case JAVASCRIPT = 'javascript';
    case PYTHON = 'python';
    case RUBY = 'ruby';
    case JAVA = 'java';
    case CSHARP = 'csharp';
    case GO = 'go';
    case CPLUSPLUS = 'cpp';
    case C = 'c';
    case DART = 'dart';

    public static function rule(): EnumRule
    {
        return new EnumRule(self::class);
    }
}
