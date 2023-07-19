<?php

namespace App\Repository;

use App\Models\User;

class UserRepository extends AbstractRepository
{
    protected static $model = User::class;
}
