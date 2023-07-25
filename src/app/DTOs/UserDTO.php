<?php

namespace App\DTOs;

use App\Enums\DocumentTypeEnum;
use App\Enums\UserTypeEnum;
use Illuminate\Support\Arr;

final class UserDTO
{
    private string $name;
    private string $email;
    private string $type;
    private string $documentType;
    private string $documentNumber;

    public static function fromArray(array $payload): self
    {
        return (new self())->get($payload);
    }

    private function get(array $payload): self
    {
        $this->name = Arr::get($payload, 'name');
        $this->email = Arr::get($payload, 'email');
        $this->type = Arr::get($payload, 'type');
        $this->documentType = Arr::get($payload, 'document_type');
        $this->documentNumber = Arr::get($payload, 'document_number');

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getType(): UserTypeEnum
    {
        return UserTypeEnum::from($this->type);
    }

    public function getDocumentType(): DocumentTypeEnum
    {
        return DocumentTypeEnum::from($this->documentType);
    }

    public function getDocumentNumber(): string
    {
        return $this->documentNumber;
    }
}