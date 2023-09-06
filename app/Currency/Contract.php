<?php

namespace App\Currency;

interface Contract {
    public function getRate(string $base, string $currency): float;
}
