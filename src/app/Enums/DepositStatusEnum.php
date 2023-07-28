<?php

namespace App\Enums;

enum DepositStatusEnum: string
{
    case Opened = 'OPENED';
    case Closed = 'CLOSED';
    case Successful = 'SUCCESSFUL';
    case Failed = 'FAILED';

    public static function all(): array
    {
        return array_column(self::cases(), 'value');
    }
}