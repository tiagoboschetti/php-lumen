<?php

namespace App\Interfaces;

use App\DTOs\UserDTO;
use App\Models\UserModel;

interface UserRepositoryInterface
{
    public function store(UserDTO $userDto): UserModel;
}