<?php

namespace App\Services;

use App\Enums\TransactionStatusEnum;
use App\Interfaces\PayableInterface;
use App\Interfaces\ReceivableInterface;
use App\Models\TransactionModel;
use App\Models\DepositModel;
use App\Models\UserModel;
use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Support\Facades\Log;

class NaturalUserService implements ReceivableInterface, PayableInterface
{
    private const AUTHORIZED = 'Autorizado';

    private PaymentAuthorizationHttpClient $client;
    private UserModel $user;

    public function __construct(UserModel $user)
    {
        $this->user = $user;
        $this->client = new PaymentAuthorizationHttpClient(app(GuzzleClient::class));
    }

    public function pay(TransactionModel $transaction): void
    {
        $response = $this->dispachRequestToAuthorize();

        if ($response['message'] !== self::AUTHORIZED) {
            $this->failedTransactionAndCry($transaction);
        }

        $this->user->wallet->available_balance = $this->user->wallet->available_balance - $transaction->amount;
        $this->user->wallet->save();
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

    protected function dispachRequestToAuthorize(): array 
    {
        $result = $this->client->authorize();

        if (!$contents = $result->getBody()->getContents()) {
            $errorMessage = 'Error retrieving information from API.';
            Log::error($errorMessage);
            throw new \Exception($errorMessage);
        }

        return json_decode($contents, true);
    }

    private function failedTransactionAndCry(TransactionModel $transaction): void
    {
        $transaction->status = TransactionStatusEnum::Failed;
        $transaction->save();

        $message = 'Transaction Failed. Not Authorized.';
        Log::info($message);
        throw new \Exception($message);
    }

    public function notify(): void
    {
        ## build notify service
    }
}