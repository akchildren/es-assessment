<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurrencyRate extends Model
{
    use HasFactory;

    public const RATES = [
        'EUR' => [
            'EUR' => 1,
            'GBP' => 0.85,
            'USD' => 1.09,
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
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'parent_currency_rate',
        'target_currency_rate',
        'rate',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'parent_currency_rate' => 'string',
        'target_currency_rate' => 'string',
        'rate' => 'decimal:2',
    ];
}
