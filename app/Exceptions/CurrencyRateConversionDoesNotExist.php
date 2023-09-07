<?php

namespace App\Exceptions;

use Exception;

class CurrencyRateConversionDoesNotExist extends Exception
{
    public function __construct(
        string $message = 'Currency rate does not exist',
        int $code = 404,
        Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
    // Throw
}
