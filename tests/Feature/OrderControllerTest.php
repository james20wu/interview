<?php

namespace Tests\Feature;

use App\Models\Order\Order;
use Database\Factories\Order\OrderFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class OrderControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithoutMiddleware;
    /**
     * A basic test example.
     */

    public function test_store_order_return_422_response(): void
    {
        $orderFactory = new OrderFactory();
        $data         =  $orderFactory->definition();
        unset($data['id']);

        $this->json('POST', '/api/orders', $data, ['Accept' => 'application/json'])
            ->assertStatus(422)
            ->assertJson([
                'success' => false,
            ]);
    }

    public function test_store_order_return_200_response(): void
    {
        $orderFactory = new OrderFactory();
        $data         = $orderFactory->definition();

        $this->json('POST', '/api/orders', $data, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $data['address'] = $this->castAsJson($data['address']);
        $this->assertDatabaseHas('orders', $data);
        $this->assertDatabaseHas('orders_'. $data['currency'], $data);
    }
}
