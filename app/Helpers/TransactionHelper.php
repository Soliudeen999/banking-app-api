<?php

namespace App\Helpers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TransactionHelper
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public static function verifyUserPin(User $user, string $pin): bool
    {
        if(Hash::check($pin, $user->trnx_pin)) {
            return true;
        }

        throw new \InvalidArgumentException('Invalid transaction PIN');
    }
}
