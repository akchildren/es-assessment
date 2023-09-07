<?php

namespace App\Exceptions;

use Exception;

class XBaseCurrencyException extends Exception
{
    public function __construct(
        string $message = 'X-Base-Currency header required',
        int $code = 400,
        Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
    // Throw
}
