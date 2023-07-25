<?php

namespace Tests\Unit\Services;

use App\Enums\TransactionStatusEnum;
use App\Enums\UserTypeEnum;
use App\Models\TransactionModel;
use App\Models\UserModel;
use App\Models\WalletModel;
use App\Services\NaturalUserService;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Str;
use Illuminate\Testing\Assert;
use Mockery;
use Tests\MockGuzzleClient;
use Tests\TestCase;

class NaturalUserServiceTest extends TestCase
{
    use MockGuzzleClient;

    private UserModel $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = UserModel::factory(['type' => UserTypeEnum::Natural])->create();
        $this->user->wallet = WalletModel::factory(['user_id' => $this->user->id, 'available_balance' => 0])->create();
    }

    public function test_Pay_ShouldAuthorizeAndDecreaseAmountFromBalance(): void
    {
        $this->setUpGuzzleClientForOK();

        $balance = $this->user->wallet->available_balance;

        $service = new NaturalUserService($this->user);
        $transaction = $this->createTransaction($amount = 99.99);
        $service->pay($transaction);

        Assert::assertEquals($balance - $amount, $this->user->wallet->available_balance);
    }

    public function test_Pay_ShouldDoNotAuthorizeThrowException(): void
    {
        $this->setUpGuzzleClientForError();
        $this->expectExceptionMessage('Transaction Failed. No Authorized.');

        $service = new NaturalUserService($this->user);
        $transaction = $this->createTransaction($amount = 99.99);
        $service->pay($transaction);

        Assert::assertEquals(TransactionStatusEnum::Failed, $transaction->status);
    }

    public function test_ReceiveMoney_ShouldReceiveNeededMoneyAndAddInWallet(): void
    {
        $service = new NaturalUserService($this->user);

        $transaction = $this->createTransaction($amount = 100);
        $service->receiveMoney($transaction);

        Assert::assertEquals($amount, $this->user->wallet->available_balance);
    }

    public function test_ReceiveMoney_ShouldReceiveMoneyAsFloatAndAddInWallet(): void
    {
        $service = new NaturalUserService($this->user);

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

    private function setUpGuzzleClientForOK(): void
    {
        $this->mockGuzzleClient([
            new Response(200, [], '{
                "message": "Autorizado"
            }'),
        ]);
    }

    private function setUpGuzzleClientForError(): void
    {
        $this->mockGuzzleClient([
            new Response(200, [], '{
                "message": "Error"
            }'),
        ]);
    }
}