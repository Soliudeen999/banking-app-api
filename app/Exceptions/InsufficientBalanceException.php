<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class InsufficientBalanceException extends Exception
{
    public function __construct(
        string $message = "Insufficient Account Balance",
        int $code = 0,
        Throwable|null $previous = null
    ){
        parent::__construct($message, $code, $previous);
    }

    public function renderable()
    {
        return response()->json([
           'message' => $this->message
        ]);
    }
}
