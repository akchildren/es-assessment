<?php

namespace App\Currency;

class Adapter implements Contract
{
    public function getRate(string $base, string $currency): float
    {
        // TODO: Implement getTaxRate() method.
    }
}
