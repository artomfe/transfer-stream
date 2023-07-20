<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use App\Models\User;

class UserTest extends TestCase
{
    // Testando o método que verifica se o usuário é pessoa física ou lojista(jurídica)
    public function testIsNaturalPerson()
    {
        // Cenário 1: O usuário é pessoa física
        $user = new User();
        $user->type = 1;
        $this->assertTrue($user->isNaturalPerson());

        // Cenário 2: O usuário não é pessoa física
        $user->type = 2;
        $this->assertFalse($user->isNaturalPerson());
    }
}
