<?php

namespace App\Listeners\Order;

use App\Events\Order\OrderCreated;
use App\Services\OrderService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class OrderStore implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct(
        protected OrderService $orderService
    ) {}

    /**
     * Handle the event.
     */
    public function handle(OrderCreated $event): void
    {
        $this->orderService->createOrder($event->order_data);
    }
}
