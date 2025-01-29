<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;

class OrderService
{
    public function createOrder(int $userId, array $items): JsonResponse
    {
        $user = User::findOrFail($userId);

        return DB::transaction(function () use ($user, $items) {
            $order = Order::create([
                'order_number' => uniqid('', true),
                'user_id' => $user->id
            ]);

            foreach ($items as $item) {
                $product = Product::findOrFail($item['product_id']);
                if ($product->stock < $item['quantity']) {
                    return response()->json(['error' => 'Not enough stock'], 400);
                }

                $product->stock -= $item['quantity'];
                $product->save();

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price
                ]);
            }

            return response()->json($order);
        });
    }

    public function approveOrder(string $orderNumber): JsonResponse
    {
        $order = Order::where('order_number', $orderNumber)->with('items.product')->firstOrFail();
        $user = $order->user;

        $total = $order->items->sum(fn($item) => $item->quantity * $item->price);
        if ($user->balance < $total) {
            return response()->json(['error' => 'Insufficient balance'], 400);
        }

        return DB::transaction(function () use ($order, $user, $total) {
            $user->balance -= $total;
            $user->save();
            $order->update(['status' => 'approved']);

            return response()->json(['message' => 'Order approved']);
        });
    }
}