<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GovtAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_number', 'bank_code', 'account_name', 'balance', 'status', 'account_type'
    ];
}
