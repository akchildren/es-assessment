<?php

namespace App\Http\Resources;

use App\Currency\CurrencyPrices;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * @var array
     */
    private array $prices;

    /**
     * @param Product $resource
     */
    public function __construct(Product $resource)
    {
        $this->prices = $resource->getAllCurrencyPrices();
        parent::__construct($resource);
    }

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
            'currencies' => $this->prices
        ];
    }
}
