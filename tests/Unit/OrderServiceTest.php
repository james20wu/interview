<?php

namespace Tests\Unit;

use App\Models\Order\Order;
use App\Models\Order\OrderTWD;
use App\Services\OrderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderServiceTest extends TestCase
{
     use RefreshDatabase;

    public function test_function_get_currency_order_model()
    {
        $order = (new OrderService())->getCurrencyOrderModel(OrderService::CURRENCY_TWD);
        $this->assertInstanceOf(OrderTWD::class, $order);
    }

    public function test_function_get_currency_order_model_fail()
    {
        $this->assertThrows(
            fn () =>  (new OrderService())->getCurrencyOrderModel('test'),
            \Exception::class
        );
    }

    public function test_function_get_order_by_id()
    {
        $order = Order::factory()->create();
        $order = (new OrderService())->getOrderById($order->id);
        $this->assertInstanceOf(Order::class, $order);
    }
}
