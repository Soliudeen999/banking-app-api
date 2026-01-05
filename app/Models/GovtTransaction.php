<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GovtTransaction extends Model
{
    protected $fillable = [
        'reference',
        'account_number',
        'bank_code',
        'narration',
        'related_account_number',
        'related_bank_code',
        'amount',
        'type',
        'status'
    ];

    public function fromAccount()
    {
        return $this->belongsTo(GovtAccount::class, 'account_number', 'account_number');
    }

    public function toAccount()
    {
        return $this->belongsTo(GovtAccount::class, 'related_account_number', 'account_number');
    }
}
