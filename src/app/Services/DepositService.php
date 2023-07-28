<?php

namespace App\Services;

use App\DTOs\DepositDTO;
use App\Enums\DepositStatusEnum;
use App\Enums\UserTypeEnum;
use App\Interfaces\PayableInterface;
use App\Interfaces\ReceivableInterface;
use App\Models\DepositModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

final class DepositService
{
    public function deposit(DepositDTO $depositDTO): void
    {
        # future deposit ruling

        $payee = $depositDTO->getPayee();

        $receivable = $payee->type === UserTypeEnum::Legal
            ? new LegalUserService($payee)
            : new NaturalUserService($payee);

        $this->depositMoney($receivable, $depositDTO);
    }

    private function depositMoney(ReceivableInterface $receiveable, DepositDTO $depositDTO): void
    {
        try {
            $deposit = new DepositModel([
                'status' => DepositStatusEnum::Opened,
                'amount' => $depositDTO->getAmount(),
                'payee_id' => $depositDTO->getPayeeId(),
            ]);

            $deposit->status = DepositStatusEnum:: Successful;
            $deposit->save();

            DB::transaction(function () use ($receiveable, $deposit) {
                $receiveable->depositMoney($deposit);
            });
        } catch (\Exception $e) {
            if ($deposit) {
                $deposit->status = DepositStatusEnum::Failed;
                $deposit->save();
            }
            throw $e;
        }
    }
}