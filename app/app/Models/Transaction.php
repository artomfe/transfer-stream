<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'value',
        'payer_id',
        'payee_id',
        'status',
        'processed_at',
    ];

    // Transaction status
    const statusByID = [
        1 => "on going",
        2 => "successful",
        3 => "refused",
    ];

    public function payer()
    {
        return $this->belongsTo(Wallet::class, 'payer_id');
    }

    public function payee()
    {
        return $this->belongsTo(Wallet::class, 'payee_id');
    }
}
