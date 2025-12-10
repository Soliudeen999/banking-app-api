<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountTransaction extends Model
{
    protected $fillable = [
        'account_id',
        'type',
        'amount',
        'balance_before',
        'balance_after',
        'narration',
        'reference',
        'status',
        'to_account_number',
        'to_bank_name',
        'to_account_name',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
