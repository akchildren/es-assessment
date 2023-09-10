<?php

namespace App\Models;

use App\Currency\Adapter;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Product extends Model
{
    use HasFactory,
        HasSlug;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'sluggable',
        'base_currency',
        'price',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'title' => 'string',
        'base_currency' => 'string',
        'price' => 'decimal:2',
    ];

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Interact with the user's first name.
     */
    protected function price(): Attribute
    {
        return Attribute::make(
            set: static fn (int $value) => number_format($value / 100, 2),
        );
    }

    /**
     * @note Currency conversions should round up with a trialing 99 minor unit.
     */
    public function getAllCurrencyPrices(): array
    {
        $currencyCodes = CurrencyRate::distinct()->pluck('parent_currency_code');
        $adapter = new Adapter();
        $prices = [];

        foreach ($currencyCodes as $code) {
            $rate = $adapter->getRate($this->base_currency, $code);
            $prices[$code] = ceil($rate * $this->price) - 0.01;
        }

        return $prices;
    }
}
