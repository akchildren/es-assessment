<?php

namespace Tests\Feature;

use App\Models\User;
use App\Currency\Adapter;
use Tests\TestCase;
use Mockery;

class RateTest extends TestCase
{
    /**
     * @var string
     */
    private string $endpoint = '/api/rate';

    /**
     * @var User
     */
    private User $user;

    /**
     * @return void
     */
    private function setup(): void
    {
        parent::setup();
        $this->user = User::factory()->make();
    }

    /**
     * @return array[]
     */
    public static function currencyConversionProvider(): array
    {
        return [
            ['GBP', 'GBP', 1],
            ['GBP', 'USD', 1.28],
            ['GBP', 'EUR', 1.17],
            ['USD', 'USD', 1],
            ['USD', 'GBP', 0.78],
            ['USD', 'EUR', 0.92],
            ['EUR', 'GBP', 0.85],
            ['EUR', 'EUR', 1],
            ['EUR', 'USD', 1.09],
        ];
    }

    private function createMockAdapter($return, $base, $currency): Mockery\MockInterface
    {
        return Mockery::mock(Adapter::class, static function ($mock) use ($return, $base, $currency) {
            $mock->shouldReceive('getRate')->with($base, $currency)->once()->andReturn($return);
        });
    }

    /**
     * @dataProvider currencyConversionProvider
     */
    public function test_it_returns_the_converted_currency(string $base, string $currency, float $rate): void
    {
        $this->app->instance(Adapter::class, $this->createMockAdapter($rate, $base, $currency));

        $this
            ->actingAs($this->user, 'sanctum')
            ->withHeaders(['X-Base-Currency' => $base])
            ->get("{$this->endpoint}?currency={$currency}")
            ->assertStatus(200)
            ->assertJson(['data' => ['rate' => $rate]]);
    }

    public function test_it_returns_404_for_unknown(): void
    {
        $this
            ->actingAs($this->user, 'sanctum')
            ->withHeaders(['X-Base-Currency' => 'DOES_NOT_EXIST'])
            ->get($this->endpoint)
            ->assertStatus(404);
    }

    public function test_it_returns_404_for_gbp_to_unknown(): void
    {
        $this
            ->actingAs($this->user, 'sanctum')
            ->withHeaders(['X-Base-Currency' => 'DOES_NOT_EXIST'])
            ->get($this->endpoint)
            ->assertStatus(404);
    }

    public function test_it_allows_an_override_when_passing_a_query_parameter(): void
    {
        $this->app->instance(Adapter::class, $this->createMockAdapter(1, 'GBP', 'GBP'));

        $this
            ->actingAs($this->user, 'sanctum')
            ->withHeaders(['X-Base-Currency' => 'USD'])->get("{$this->endpoint}?base=GBP&currency=GBP")
            ->assertStatus(200)
            ->assertJson(['data' => ['rate' => 1]]);
    }
}
