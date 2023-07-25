<?php

namespace App\Enums;

enum TransactionStatusEnum: string
{
    case Opened = 'OPENED';
    case Closed = 'CLOSED';
    case Waiting = 'WAITING';
    case Failed = 'FAILED';
    case Successful = 'SUCCESSFUL';

    public static function all(): array
    {
        return array_column(self::cases(), 'value');
    }
}