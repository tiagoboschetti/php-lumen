<?php

namespace App\DTOs;

use App\Models\UserModel;
use Illuminate\Support\Arr;

final class TransactionDTO
{
    private float $amount;
    private int $payer;
    private int $payee;

    public static function fromArray(array $payload): self
    {
        return (new self())->get($payload);
    }

    private function get(array $payload): self
    {
        $this->amount = Arr::get($payload, 'amount');
        $this->payer = Arr::get($payload, 'payer');
        $this->payee = Arr::get($payload, 'payee');

        return $this;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getPayerId(): int
    {
        return $this->payer;
    }

    public function getPayer(): UserModel
    {
        return UserModel::where('id', $this->payer)->firstOrFail();
    }

    public function getPayeeId(): int
    {
        return $this->payee;
    }

    public function getPayee(): UserModel
    {
        return UserModel::where('id', $this->payee)->firstOrFail();
    }
}