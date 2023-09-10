<?php

namespace App\Http\Resources;

use App\Currency\Adapter;
use App\Models\CurrencyRate;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'title' => $this->title,
            'slug' => $this->slug,
            'price' => $this->price,
            'base_currency' => $this->base_currency,
            'currencies' => $this->getAllCurrencyPrices(),
        ];
    }

    /**
     * @note Currency conversions should round up with a trialing 99 minor unit.
     * @return array
     */
    protected function getAllCurrencyPrices(): array
    {
        $adapter = new Adapter();
        $currencyCodes = CurrencyRate::distinct()->pluck('parent_currency_code');
        $prices = [];
        foreach ($currencyCodes as $code) {
            $rate = $adapter->getRate($this->base_currency, $code);
            $prices[$code] = ceil($rate * $this->price) - 0.01 ;
        }
        return $prices;
    }
}
