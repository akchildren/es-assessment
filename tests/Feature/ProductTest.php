<?php

namespace Tests\Feature;

use App\Currency\Adapter;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public static function validationDataProvider(): array
    {
        return [
            [['title' => '', 'price' => 1299, 'base_currency' => 'GBP'], 422],
            [['title' => 123, 'price' => 1299, 'base_currency' => 'GBP'], 422],
            [['title' => 'Test', 'price' => null, 'base_currency' => 'GBP'], 422],
            [['title' => '', 'price' => 1299, 'base_currency' => null], 422],
            [['title' => '', 'price' => 1299, 'base_currency' => 'UNKNOWN'], 422],
        ];
    }

    /**
     * @dataProvider validationDataProvider
     */
    public function test_validation($input, $statusCode): void
    {
        $user = User::factory()->make();
        $this->actingAs($user, 'sanctum');

        $response = $this->json('POST', '/api/product', $input);
        $response->assertStatus($statusCode);
    }

    public function test_it_stores_a_valid_product(): void
    {
        $user = User::factory()->make();
        $this->actingAs($user, 'sanctum');
        $mockAdapter = \Mockery::mock(Adapter::class, static function ($mock) {
            $mock->shouldReceive('getRate')->with('GBP', 'GBP')->andReturn(1);
            $mock->shouldReceive('getRate')->with('GBP', 'USD')->andReturn(1.28);
            $mock->shouldReceive('getRate')->with('GBP', 'EUR')->andReturn(1.17);
        });

        $this->app->instance(Adapter::class, $mockAdapter);

        $data = [
            'title' => 'Product 1',
            'base_currency' => 'GBP',
            'price' => 1299,
        ];

        $response = $this->json('POST', '/api/product', $data);

        $response->assertJson([
            'data' => [
                'title' => 'Product 1',
                'slug' => 'product-1',
                'price' => 12.99,
                'base_currency' => 'GBP',
                // Currency conversions should round up with a trialing 99 minor unit. For example:
                // 19.13 => 19.99
                // 15.87 => 15.99
                'currencies' => [
                    'GBP' => 12.99,
                    'USD' => 16.99,
                    'EUR' => 15.99,
                ],
            ],
        ]);
    }
}
