<?php

namespace App\Interfaces;

use App\Models\TransactionModel;
use App\Models\DepositModel;

interface ReceivableInterface
{
    public function receiveMoney(TransactionModel $transaction): void;
    public function depositMoney(DepositModel $deposit): void;

    public function notify(): void;
}