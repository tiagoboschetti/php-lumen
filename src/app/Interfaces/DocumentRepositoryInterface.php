<?php

namespace App\Interfaces;

use App\Enums\DocumentTypeEnum;

interface DocumentRepositoryInterface
{
    public function store(DocumentTypeEnum $type, string $number, int $userId): void;
}