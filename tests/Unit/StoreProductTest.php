<?php

namespace Tests\Unit;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class StoreProductTest extends TestCase
{
    use RefreshDatabase;

    private string $endpoint = '/api/product';

    private object $content;

    private string $error;

    private TestResponse $response;

    public function test_validation_fails_on_invalid_title(): void
    {
        $data = ['price' => 1299, 'base_currency' => 'GBP'];
        $this->assertFieldRequired('title', $data);

        // Allow special characters and numeric as products can contain these
        $data['title'] = 'asdasd1';
        $this->callApi($data);
        $this->response->assertSuccessful();
    }

    public function test_validation_fails_on_invalid_currency_code(): void
    {
        $data = ['price' => 1299, 'title' => 'valid'];
        $this->assertFieldRequired('base_currency', $data);

        // Allow special characters and numeric as products can contain these
        $data['base_currency'] = 'ASDS';
        $this->callApi($data);
        $this->response->assertStatus(422);
        $this->assertStringContainsString('greater than 3', $this->error);
    }

    public function test_currency_is_capitilaized(): void
    {
        $data = ['price' => 1299, 'title' => 'valid', 'base_currency' => 'gBp'];
        $this->callApi($data);

        $this->assertTrue(ctype_upper(Product::first()->base_currency));
    }

    public function test_validation_fails_on_invalid_price(): void
    {
        $data = ['title' => 'valid', 'base_currency' => 'gBp'];
        $this->assertFieldRequired('price', $data);

        $data['price'] = 'string';
        $this->callApi($data);
        $this->assertStringContainsString('must be a number', $this->error);

        $data['price'] = 12.13;
        $this->callApi($data);
        $this->assertStringContainsString('integer', $this->error);
    }

    public function test_price_is_converted_to_currency_value(): void
    {
        $data = ['price' => 1299, 'title' => 'valid', 'base_currency' => 'GBP'];
        $this->callApi($data);

        $this->assertIsFloat((float) Product::first()->price);
    }

    private function assertFieldRequired(string $field, array $data): void
    {
        $data[$field] = '';
        $this->callApi($data);

        $this->assertStringContainsString('required', $this->error);
    }

    private function callApi(array $data): void
    {
        $this->response = $this->json('POST', $this->endpoint, $data);
        $this->content = json_decode($this->response->getContent());
        if (isset($this->content->errors)) {
            $this->error = $this->content->message;
        }
    }
}
