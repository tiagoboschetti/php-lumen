<?php

namespace App\Enums;

enum UserTypeEnum: string
{
    case Legal = 'legal';
    case Natural = 'natural';

    public static function all(): array
    {
        return array_column(self::cases(), 'value');
    }
}