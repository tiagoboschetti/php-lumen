<?php

namespace App\Services;

use App\Interfaces\ReceivableInterface;
use App\Models\TransactionModel;
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
    }

    public function notify(): void
    {
        // implementar serviço de envio de notificação
        // cenário ideal seria jogar para um sqs/kafka para enviar assincronamente
        // e se ao acaso serviço estiver disponivel, criar um command/job para retentar
    }
}