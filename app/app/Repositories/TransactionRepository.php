<?php

namespace App\Repositories;

use App\Models\Transaction;

class TransactionRepository extends AbstractRepository
{
    protected static $model = Transaction::class;
}
