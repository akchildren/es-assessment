<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CurrencyRateSeeder extends Seeder
{
    private array $rates = [
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

    public function run(): void
    {
        foreach ($this->rates as $parentCode => $rates) {
            foreach ($rates as $targetCode => $rate) {
                DB::table('currency_rates')->insert([
                    'parent_currency_code' => $parentCode,
                    'target_currency_code' => $targetCode,
                    'rate' => $rate,
                ]);
            }
        }
    }
}
