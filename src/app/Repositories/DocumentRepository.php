<?php

namespace App\Repositories;

use App\Enums\DocumentTypeEnum;
use App\Interfaces\DocumentRepositoryInterface;
use App\Models\DocumentModel;
use Illuminate\Database\QueryException;

class DocumentRepository implements DocumentRepositoryInterface
{
    public function store(DocumentTypeEnum $type, string $number, int $userId): void
    {
        try {
            $document = new DocumentModel([
                'type' => $type->value,
                'number' => $number,
                'user_id' => $userId,
            ]);

            $document->save();
        } catch (\Exception|QueryException $e) {
            throw new \Exception($e->getMessage(), 500);
        }
    }
}