<?php

namespace App\Services;

use App\Enums\DocumentTypeEnum;
use App\Repositories\DocumentRepository;
use App\Repositories\WalletRepository;

final class UserAssetService
{
    public static function saveDocument(DocumentTypeEnum $type, string $number, int $userId): void
    {
        $documentRepository = new DocumentRepository();
        $documentRepository->store($type, $number, $userId);
    }

    public static function createWallet(int $userId): void
    {
        $walletRepository = new WalletRepository();
        $walletRepository->store($userId);
    }
}