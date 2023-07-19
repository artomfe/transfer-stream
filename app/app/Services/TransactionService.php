<?php

namespace App\Services;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Repository\UserRepository;
use App\Repository\TransactionRepository;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class TransactionService
{
    private $userRepository;
    private $transactionRepository;
    private $notificationService;

    public function __construct(
        UserRepository $userRepository, 
        TransactionRepository $transactionRepository,
        NotificationService $notificationService
    )
    {
        $this->userRepository = $userRepository;
        $this->transactionRepository = $transactionRepository;
        $this->notificationService = $notificationService;
    }

    public function makeTransaction(int $payerId, float $amount, int $payeeId) : JsonResponse
    {
        $payer = $this->userRepository::loadModel()->with('wallet')->find($payerId);
        $payee = $this->userRepository::loadModel()->with('wallet')->find($payeeId);

        $this->validateTransaction($payer, $amount);

        $transaction = $this->createTransaction($payer->wallet->id, $payee->wallet->id, $amount);

        try {
            $transactionToReturn = $this->processTransaction($payer, $payee, $amount, $transaction);

            $this->notificationService->sendNotification($payee, $amount, $transaction->id);

            return response()->json(['message' => 'Transação realizada com sucesso!', 'data' => $transactionToReturn]);
        } catch (\Exception $e) {
            $this->handleTransactionError($transaction);

            throw new HttpException(400, 'Ocorreu um erro ao processar sua transação.');
        }
    }

    private function validateTransaction($payer, $amount)
    {
        if (!$payer->isNaturalPerson()) {
            throw new HttpException(400, 'Usuário é lojista. Transação não permitida.');
        }

        if ($payer->wallet->balance < $amount) {
            throw new HttpException(400, 'Saldo insuficiente. Transação não permitida.');
        }
    }

    private function createTransaction($payerWalletId, $payeeWalletId, $amount) 
    {
        $transaction = $this->transactionRepository->create([
            'value' => $amount,
            'payer_id' => $payerWalletId,
            'payee_id' => $payeeWalletId,
        ]);

        $transaction->save();

        return $transaction;
    }

    private function processTransaction($payer, $payee, $amount, $transaction)
    {
        // Make an external authorization request
        $response = Http::get('https://run.mocky.io/v3/8fafdd68-a090-496f-8c9a-3442cf30dae6');

        // Check if the authorization was successful
        if (!$response->successful()) {
            // If the authorization fails, update the status of the transfer to "refused" (status 2)
            $transaction->status = 2;
            $transaction->save();

            throw new HttpException(400, 'Falha na autorização do serviço externo. Transação recusada.');
        }

        DB::beginTransaction();

        // Update the wallet balance for payer and payee
        $payer->wallet->balance -= $amount;
        $payer->wallet->save();

        $payee->wallet->balance += $amount;
        $payee->wallet->save();

        // Update the status of the transfer to "successful" (status 3)
        $transaction->status = 3; 
        $transaction->processed_at = Carbon::now();
        $transaction->save();

        DB::commit();

        return [
            "amount" => $amount,
            "payer" => $payer->id,
            "payee" => $payee->id
        ];
    }

    private function handleTransactionError($transaction)
    {
        // If there is an exception, rollback the transaction
        DB::rollback();

        // Update the status of the transfer to "refused" (status 2)
        $transaction->status = 2;
        $transaction->save();
    }
 
}
