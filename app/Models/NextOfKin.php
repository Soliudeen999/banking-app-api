<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NextOfKin extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'relationship',
        'phone',
        'address',
        'email',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
