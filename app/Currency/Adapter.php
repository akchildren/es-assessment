<?php

namespace App\Currency;

use App\Models\CurrencyRate;

class Adapter implements Contract
{
    public function getRate(string $base, string $currency): float
    {
        return CurrencyRate::whereParentCurrencyCode($base)
            ->whereTargetCurrencyCode($currency)
            ->first()
            ->pluck('rate');
    }
}
