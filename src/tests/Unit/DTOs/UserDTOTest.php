<?php

namespace Tests\Unit\DTOs;

use App\DTOs\UserDTO;
use App\Enums\DocumentTypeEnum;
use App\Enums\UserTypeEnum;
use PHPUnit\Framework\Assert;
use Tests\TestCase;

class UserDTOTest extends TestCase
{
    public function test_FromArray_ShouldFillAndReturnNeededProperties(): void
    {
        $dto = UserDTO::fromArray([
            'name' => 'Severo Snape',
            'email' => 'severo@snape.com',
            'type' => UserTypeEnum::Shopkeeper->value,
            'document_type' => DocumentTypeEnum::CPF->value,
            'document_number' => '0123456789',
        ]);

        Assert::assertEquals('Severo Snape', $dto->getName());
        Assert::assertEquals('severo@snape.com', $dto->getEmail());
        Assert::assertEquals(UserTypeEnum::Shopkeeper, $dto->getType());
        Assert::assertEquals(DocumentTypeEnum::CPF, $dto->getDocumentType());
        Assert::assertEquals('0123456789', $dto->getDocumentNumber());
    }
}