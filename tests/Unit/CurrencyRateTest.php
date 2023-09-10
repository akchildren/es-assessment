<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CurrencyRateTest extends TestCase
{
    use RefreshDatabase;

    public function test_currency_rate__throttle_limit_is_active(): void
    {
        $user = User::factory()->make();
        $this->actingAs($user, 'sanctum');

        $this->withHeaders(['X-Base-Currency' => 'GBP'])->get("/api/rate?currency=USD")
            ->assertOk()
            ->assertHeader('X-Ratelimit-Remaining', 49);
    }
}
