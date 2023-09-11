<?php

namespace App\Actions\Currency;

use App\Currency\Contract;
use App\Models\CurrencyRate;
use App\Models\Product;

readonly class GetAllProductPrices
{
    public function __construct(
        private Contract $contract
    ) {
    }

    public function execute(Product $product): array
    {
        $currencyCodes = CurrencyRate::distinct()->pluck('parent_currency_code');
        $prices = [];

        foreach ($currencyCodes as $code) {
            $rate = $this->contract->getRate($product->base_currency, $code);
            $prices[$code] = ceil($rate * $product->price) - 0.01;
        }

        return $prices;
    }
}
