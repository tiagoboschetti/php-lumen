<?php

namespace Tests\Unit\Services;

use App\DTOs\DepositDTO;
use App\Enums\UserTypeEnum;
use App\Models\UserModel;
use App\Models\WalletModel;
use App\Services\DepositService;
use Illuminate\Testing\Assert;
use Tests\TestCase;

class DepositServiceTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_Deposit_ShouldDepositAndUpdateBalancesFromPayee()
    {
        $payee = UserModel::factory(['type' => UserTypeEnum::Natural])->create();
        $payee->wallet = WalletModel::factory(['user_id' => $payee->id, 'available_balance' => $payeeBalance = 0])->create();

        $depositDTO = DepositDTO::fromArray([
            'amount' => 5000,
            'payee' => $payee->id,
        ]);

        (new DepositService())->deposit($depositDTO);

        $payee = $depositDTO->getPayee($payee);
        Assert::assertEquals(5000, $payee->wallet->available_balance);
    }
}