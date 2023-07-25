<?php

namespace App\Interfaces;

interface WalletRepositoryInterface
{
    public function store(int $userId): void;
}