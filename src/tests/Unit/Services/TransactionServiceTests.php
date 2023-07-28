<?php

namespace Tests\Unit\Services;

use App\DTOs\TransactionDTO;
use App\Enums\UserTypeEnum;
use App\Models\UserModel;
use App\Models\WalletModel;
use App\Services\TransactionService;
use Illuminate\Testing\Assert;
use Tests\TestCase;

class TransactionServiceTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_PayOrCry_ShouldPayAndUpdateBalancesFromPayerAndPayee()
    {
        $payer = UserModel::factory(['type' => UserTypeEnum::Natural])->create();
        $payer->wallet = WalletModel::factory(['user_id' => $payer->id, 'available_balance' => $payerBalance = 1500])->create();

        $payee = UserModel::factory(['type' => UserTypeEnum::Legal])->create();
        $payee->wallet = WalletModel::factory(['user_id' => $payee->id, 'available_balance' => $payeeBalance = 1000])->create();

        $transactionDTO = TransactionDTO::fromArray([
            'amount' => 500,
            'payer' => $payer->id,
            'payee' => $payee->id,
        ]);

        (new TransactionService())->payOrCry($transactionDTO);

        Assert::assertEquals(1000, $payer->wallet->available_balance);
        Assert::assertEquals(1500, $payee->wallet->available_balance);
    }

    public function test_PayOrCry_ShouldCryIfPayerIsLegalUser(): void
    {
        $this->expectExceptionMessage('Legal users cannot perform transactions.');

        $payer = UserModel::factory(['type' => UserTypeEnum::Legal])->create();
        $transactionDTO = $this->createTransactionDTO($payer);

        $service = new TransactionService();
        $service->payOrCry($transactionDTO);
    }

    public function test_PayOrCry_ShouldCryIfPayerHasInsufficientBalance(): void
    {
        $this->expectExceptionMessage('Insufficient balance!');

        $payer = UserModel::factory(['type' => UserTypeEnum::Natural])->create();
        $payer->wallet = WalletModel::factory(['user_id' => $payer->id, 'available_balance' => 100])->create();

        $transactionDTO = $this->createTransactionDTO($payer);

        $service = new TransactionService();
        $service->payOrCry($transactionDTO);
    }

    private function createTransactionDTO(UserModel $payer): TransactionDTO
    {
        return TransactionDTO::fromArray([
            'amount' => 1000,
            'payer' => $payer->id,
            'payee' => 2,
        ]);
    }
}