<?php

namespace App\Services;

use App\Interfaces\ReceivableInterface;
use App\Models\TransactionModel;
use App\Models\DepositModel;
use App\Models\UserModel;

class LegalUserService implements ReceivableInterface
{
    private UserModel $user;

    public function __construct(UserModel $user)
    {
        $this->user = $user;
    }

    public function receiveMoney(TransactionModel $transaction): void
    {
        $this->user->wallet->available_balance = $this->user->wallet->available_balance + $transaction->amount;
        $this->user->wallet->save();
        //$this->notify();
    }

    public function depositMoney(DepositModel $deposit): void
    {
        $this->user->wallet->available_balance = $this->user->wallet->available_balance + $deposit->amount;
        $this->user->wallet->save();
    }

    public function notify(): void
    {
        ## build notify service
    }
}