<?php

namespace App\Services;

use App\DTOs\TransactionDTO;
use App\Enums\TransactionStatusEnum;
use App\Enums\UserTypeEnum;
use App\Interfaces\PayableInterface;
use App\Interfaces\ReceivableInterface;
use App\Models\TransactionModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

final class TransactionService
{
    public function payOrCry(TransactionDTO $transactionDTO): void
    {
        $payer = $transactionDTO->getPayer();
        $payee = $transactionDTO->getPayee();

        if ($payer->type === UserTypeEnum::Legal) {
            throw new \Exception('Legal users cannot perform transactions.', 400);
        }

        if ($payer->wallet->available_balance <= $transactionDTO->getAmount()) {
            throw new \Exception('Insufficient balance!', 400);
        }

        $payable = new NaturalUserService($payer);

        $receivable = $payee->type === UserTypeEnum::Legal
            ? new LegalUserService($payee)
            : new NaturalUserService($payee);


        $this->pay($payable, $receivable, $transactionDTO);
    }

    private function pay(PayableInterface $payable, ReceivableInterface $receiveable, TransactionDTO $transactionDTO): void
    {
        try {
            $transaction = new TransactionModel([
                'status' => TransactionStatusEnum::Opened,
                'token' => Str::uuid()->toString(),
                'amount' => $transactionDTO->getAmount(),
                'payer_id' => $transactionDTO->getPayerId(),
                'payee_id' => $transactionDTO->getPayeeId(),
            ]);

            $transaction->status = TransactionStatusEnum:: Successful;
            $transaction->save();

            DB::transaction(function () use ($payable, $receiveable, $transaction) {
                $payable->pay($transaction);
                $receiveable->receiveMoney($transaction);
            });
        } catch (\Exception $e) {
            if ($transaction) {
                $transaction->status = TransactionStatusEnum::Failed;
                $transaction->save();
            }
            throw $e;
        }
    }
}