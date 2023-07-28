<?php

namespace Tests\Unit\Services;

use App\Enums\TransactionStatusEnum;
use App\Enums\UserTypeEnum;
use App\Models\TransactionModel;
use App\Models\UserModel;
use App\Models\WalletModel;
use App\Services\LegalUserService;
use Illuminate\Support\Str;
use Illuminate\Testing\Assert;
use Tests\TestCase;

class LegalUserServiceTest extends TestCase
{
    private UserModel $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = UserModel::factory(['type' => UserTypeEnum::Legal])->create();
        $this->user->wallet = WalletModel::factory(['user_id' => $this->user->id, 'available_balance' => 0])->create();
    }

    public function test_ReceiveMoney_ShouldReceiveNeededMoneyAndAddToWallet(): void
    {
        $service = new LegalUserService($this->user);

        $transaction = $this->createTransaction($amount = 100);
        $service->receiveMoney($transaction);

        Assert::assertEquals($amount, $this->user->wallet->available_balance);
    }

    public function test_ReceiveMoney_ShouldReceiveMoneyFloatAndAddToWallet(): void
    {
        $service = new LegalUserService($this->user);

        $transaction = $this->createTransaction($amount = 199.45);
        $service->receiveMoney($transaction);

        Assert::assertEquals($amount, $this->user->wallet->available_balance);
    }

    private function createTransaction(float $amount): TransactionModel
    {
        return new TransactionModel([
            'status' => TransactionStatusEnum::Opened,
            'token' => Str::uuid()->toString(),
            'amount' => $amount,
            'payer_id' => 1,
            'payee_id' => 2,
        ]);
    }
}