<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GovtAccount extends Model
{
    protected $fillable = [
        'account_number', 'bank_code', 'account_name', 'balance', 'status', 'account_type'
    ];
}
