<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurrencyRate extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'parent_currency_code',
        'target_currency_code',
        'rate',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'parent_currency_code' => 'string',
        'target_currency_code' => 'string',
        'rate' => 'decimal:2',
    ];

    protected function parentCurrencyCode(): Attribute
    {
        return Attribute::make(
            set: static fn (string $value) => strtoupper($value),
        );
    }

    protected function targetCurrencyCode(): Attribute
    {
        return Attribute::make(
            set: static fn (string $value) => strtoupper($value),
        );
    }
}
