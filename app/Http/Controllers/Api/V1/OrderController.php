<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateOrderRequest;
use App\Http\Requests\ApproveOrderRequest;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;

class OrderController extends Controller
{
    private OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function createOrder(CreateOrderRequest $request): JsonResponse
    {
        return $this->orderService->createOrder($request->user_id, $request->items);
    }

    public function approveOrder(ApproveOrderRequest $request): JsonResponse
    {
        return $this->orderService->approveOrder($request->order_number);
    }
}
