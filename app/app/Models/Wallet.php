<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $fillable = [
        'user_id',
        'balance',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactionsAsPayer()
    {
        return $this->hasMany(Transaction::class, 'payer_id');
    }

    public function transactionsAsPayee()
    {
        return $this->hasMany(Transaction::class, 'payee_id');
    }
}
