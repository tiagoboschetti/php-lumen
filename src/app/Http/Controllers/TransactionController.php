<?php

namespace App\Http\Controllers;

use App\DTOs\TransactionDTO;
use App\Services\TransactionService;
use Illuminate\Http\Request;

class TransactionController extends BaseController
{
    public function create(Request $request)
    {
        $this->validate($request, [
            'amount' => 'required|digits_between:0,999999.99',
            'payer' => 'required|integer|exists:users,id',
            'payee' => 'required|integer|exists:users,id',
        ]);

        try {
            $transactionDTO = TransactionDTO::fromArray($request->all());

            $service = new TransactionService();
            $service->payOrCry($transactionDTO);

            return $this->responseData('Payment successful!');
        } catch (\Exception $e) {
            return $this->responseData($e->getMessage(), $e->getCode());
        }
    }
}