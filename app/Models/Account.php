<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends Model
{
    use SoftDeletes;

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

    public function casts(): array
    {
        return [
            //
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(AccountTransaction::class);
    }
}
