<?php

namespace App\Http\Controllers;

use App\DTOs\UserDTO;
use App\Enums\DocumentTypeEnum;
use App\Enums\UserTypeEnum;
use App\Http\Resources\UserResource;
use App\Interfaces\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Enum;

class UserController extends BaseController
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'type' => ['required', new Enum(UserTypeEnum::class)],
            'document_type' => ['required', new Enum(DocumentTypeEnum::class)],
            'document_number' => 'required|string|unique:documents,number',
        ]);

        try {
            $userDto = UserDTO::fromArray($request->all());

            return new UserResource($this->userRepository->store($userDto));
        } catch (\Exception $e) {
            return $this->responseData($e->getMessage(), $e->getCode());
        }
    }
}