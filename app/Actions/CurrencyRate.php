<?php

namespace App\Actions;

use App\Exceptions\CurrencyRateConversionDoesNotExist;

class CurrencyRate
{
    /**
     * @var array|array[]
     */
    private array $rates = [
        'EUR' => [
            'EUR' => 1,
            'GBP' => 0.85,
            'USD' => 11.09,
        ],
        'GBP' => [
            'EUR' => 1.17,
            'GBP' => 1,
            'USD' => 1.28,
        ],
        'USD' => [
            'EUR' => 0.92,
            'GBP' => 0.78,
            'USD' => 1,
        ],
    ];

    /**
     * @throws CurrencyRateConversionDoesNotExist
     */
    public function convert(string $base, string $currencyCode): float
    {
        $this->isValidRate($base, $currencyCode);

        return round($this->rates[$base][$currencyCode], 2);
    }

    /**
     * @throws CurrencyRateConversionDoesNotExist
     */
    private function isValidRate(string $base, string $currencyCode): void
    {
        if (! isset($this->rates[$base]) || ! array_column($this->rates, $currencyCode)) {
            throw new CurrencyRateConversionDoesNotExist();
        }
    }
}
