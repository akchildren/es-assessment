<?php

namespace Database\Seeders;

use App\Models\CurrencyRate;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CurrencyRateSeeder extends Seeder
{
    public function run(): void
    {
        foreach (CurrencyRate::RATES as $parentCode => $rates) {
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
