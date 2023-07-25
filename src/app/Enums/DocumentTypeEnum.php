<?php

namespace App\Enums;

enum DocumentTypeEnum: string
{
    case CNPJ = 'cnpj';
    case CPF = 'cpf';

    public static function all(): array
    {
        return array_column(self::cases(), 'value');
    }
}