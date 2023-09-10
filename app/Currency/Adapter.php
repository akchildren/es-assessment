<?php

namespace App\Currency;

use App\Models\CurrencyRate;

class Adapter implements Contract
{
    /**
     * @param string $base
     * @param string $currency
     * @return float
     */
    public function getRate(string $base, string $currency): float
    {
        return CurrencyRate::whereParentCurrencyCode($base)
            ->whereTargetCurrencyCode($currency)
            ->first()
            ->rate;
    }
}
