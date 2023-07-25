<?php

namespace App\Repositories;

use App\Interfaces\WalletRepositoryInterface;
use App\Models\WalletModel;
use Illuminate\Database\QueryException;

class WalletRepository implements WalletRepositoryInterface
{
    public function store(int $userId): void
    {
        try {
            $wallet = new WalletModel([
                'available_balance' => 0,
                'user_id' => $userId,
            ]);

            $wallet->save();
        } catch (\Exception|QueryException $e) {
            throw new \Exception($e->getMessage(), 500);
        }
    }
}