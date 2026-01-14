<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CashDeposit extends Model
{
    protected $fillable = [
        'staff_id',
        'depositor_name',
        'branch',
        'depositor_phone',
        'amount',
        'account_number',
        'account_name',
        'reference',
        'narration',
        'status',
    ];

    public function staff(): BelongsTo
    {
        return $this->belongsTo(User::class, 'staff_id');
    }


    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'account_number', 'account_number');
    }
}
