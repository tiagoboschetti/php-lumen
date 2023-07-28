<?php

namespace App\Http\Controllers;

use App\DTOs\DepositDTO;
use App\Services\DepositService;
use Illuminate\Http\Request;

class DepositController extends BaseController
{
    public function deposit(Request $request)
    {
        $this->validate($request, [
            'amount' => 'required|digits_between:0,999999.99',
            'payee' => 'required|integer|exists:users,id',
        ]);

        try {
            $depositDTO = DepositDTO::fromArray($request->all());

            $service = new DepositService();
            $service->deposit($depositDTO);

            return $this->responseData('Deposit successful!');
        } catch (\Exception $e) {
            return $this->responseData($e->getMessage(), $e->getCode());
        }
    }
}