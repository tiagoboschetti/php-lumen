<?php

namespace App\Interfaces;

use App\Models\TransactionModel;

interface ReceivableInterface
{
    public function receiveMoney(TransactionModel $transaction): void;

    public function notify(): void;
}