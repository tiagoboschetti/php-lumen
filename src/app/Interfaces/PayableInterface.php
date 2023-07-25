<?php

namespace App\Interfaces;

use App\Models\TransactionModel;

interface PayableInterface
{
    public function pay(TransactionModel $transaction): void;
}