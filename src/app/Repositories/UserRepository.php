<?php

namespace App\Repositories;

use App\DTOs\UserDTO;
use App\Interfaces\UserRepositoryInterface;
use App\Models\UserModel;
use App\Services\UserAssetService;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserRepository implements UserRepositoryInterface
{
    public function store(UserDTO $userDto): UserModel
    {
        try {
            return DB::transaction(function () use ($userDto) {
                $user = UserModel::create([
                    'name' => $userDto->getName(),
                    'email' => $userDto->getEmail(),
                    'type' => $userDto->getType()->value,
                    'active' => true,
                    'password' => Hash::make(Str::random(14)),
                ]);

                UserAssetService::saveDocument(
                    $userDto->getDocumentType(),
                    $userDto->getDocumentNumber(),
                    $user->id,
                );

                UserAssetService::createWallet($user->id);

                return $user;
            });
        } catch (\Exception|QueryException $e) {
            throw new \Exception($e->getMessage(), 500);
        }
    }
}