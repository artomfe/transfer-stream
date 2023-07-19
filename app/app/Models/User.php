<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable, HasFactory;

    protected $fillable = [
        'name',
        'document',
        'email',
        'type',
    ];

    protected $hidden = [
        'password',
    ];

    // Tipo de usuário Pessoa física ou jurídica
    const typesByID = [
        1 => "natural person",
        2 => "legal entity",
    ];

    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }

    public function isNaturalPerson()
    {
        return $this->type === 1;
    }
}
