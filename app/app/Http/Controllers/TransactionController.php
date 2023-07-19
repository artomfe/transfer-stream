<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Services\TransactionService;

class TransactionController extends BaseController
{
    private $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function makeTransaction(Request $request) : JsonResponse
    {
        $this->validate($request, [
            'payer_id' => 'required|numeric',
            'amount' => 'required|numeric',
            'payee_id' => 'required|numeric',
        ]);

        $payerId = $request->input('payer_id');
        $amount = $request->input('amount');
        $payeeId = $request->input('payee_id');

        try {
            $this->transactionService->makeTransaction($payerId, $amount, $payeeId);
            return response()->json(['message' => 'Transação realizada com sucesso!']);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}