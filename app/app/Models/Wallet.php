<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Wallet extends Model
{
    use HasFactory;

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
