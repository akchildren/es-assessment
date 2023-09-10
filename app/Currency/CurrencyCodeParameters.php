<?php

namespace App\Currency;

use Illuminate\Http\Request;

readonly class CurrencyCodeParameters
{
    public function __construct(
        private Request $request
    ) {
    }

    public function getBaseCurrencyCode(): string
    {
        return $this->request->get('base') ?? $this->request->header('X-Base-Currency');
    }

    public function getTargetCurrencyCode(): string
    {
        return (string) $this->request->get('currency');
    }
}
