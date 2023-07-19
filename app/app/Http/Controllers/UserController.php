<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Laravel\Lumen\Routing\Controller as BaseController;

class UserController extends BaseController
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function getUsers()
    {
        $users = $this->userService->getUsers();

        return response()->json(['data' => $users, 'message' => 'Usuários listados com sucesso!']);
    }

}