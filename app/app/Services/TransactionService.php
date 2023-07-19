<?php

namespace App\Services;

use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Repository\UserRepository;
use App\Repository\TransactionRepository;

class TransactionService
{
    private $userRepository;
    private $transactionRepository;

    public function __construct(UserRepository $userRepository, TransactionRepository $transactionRepository)
    {
        $this->userRepository = $userRepository;
        $this->transactionRepository = $transactionRepository;
    }

    public function makeTransaction(int $payerId, float $amount, int $payeeId)
    {
        $payer = UserRepository::loadModel()->with('wallet')->find($payerId);
        $payee = UserRepository::loadModel()->with('wallet')->find($payeeId);


        // Verify if the user is a natural person
        if (!$payer->isNaturalPerson()) {
            throw new HttpException(400, 'Usuário é lojista. Transação não permitida.');
        }

        // Verify if the wallet has enough funds for the transaction.
        if ($payer->wallet->balance < $amount) {
            throw new HttpException(400, 'Saldo insuficiente. Transação não permitida.');
        }


    }

}
