<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GovtTransaction extends Model
{
    protected $fillable = [
        'reference', 'from_acct_number', 'from_bank_code', 'narration',
        'to_acct_number', 'to_bank_code', 'amount', 'status'
    ];
}
