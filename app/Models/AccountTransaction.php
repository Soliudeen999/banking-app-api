<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

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
        'gen_reference',
        'status',
        'related_account_number',
        'related_bank_code',
        'related_account_name',
        'category_id',
    ];

    public function sourceAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    public function destinationBank(): BelongsTo
    {
        return $this->belongsTo(Bank::class, 'related_bank_code', 'code');
    }
}
