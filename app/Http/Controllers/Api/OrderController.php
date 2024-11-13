<?php

namespace App\Http\Controllers\Api;

use App\Events\Order\OrderCreated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Order\OrderStoreRequest;
use App\Http\Resources\OrderResource;
use App\Services\OrderService;

class OrderController extends Controller
{
    protected OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * 新增訂單
     * @param  OrderStoreRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(OrderStoreRequest $request)
    {
        try {
            event(new OrderCreated($request->validated()));
        } catch (\Exception $e){
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }

        return response()->json(['success' => true], 200);
    }

/**
     * 查看訂單
     *
     * @return mixed
     */
    public function show($id)
    {
        $order = $this->orderService->getOrderById($id);

        if(!$order) {
            abort(404);
        }

        return OrderResource::make($order);
    }
}
