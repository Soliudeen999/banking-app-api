<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable = [
        'user_id',
        'account_number',
        'type',
        'ledger_balance',
        'main_balance',
        'debits',
        'credits',
        'tier',
        'currency',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(AccountTransaction::class);
    }
}
