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
        // Найдем пользователя. Если его нет, вернем ошибку.
        $user = User::find($userId);
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Проверим, что все товары существуют и есть на складе
        $totalPrice = 0;
        $products = Product::findMany(array_column($items, 'product_id')); // Загружаем все товары за один запрос

        if (count($products) !== count($items)) {
            return response()->json(['error' => 'One or more products not found'], 404);
        }

        // Проверим наличие на складе для каждого товара
        foreach ($items as $item) {
            $product = $products->firstWhere('id', $item['product_id']);
            if ($product->stock < $item['quantity']) {
                return response()->json(['error' => 'Not enough stock for product ' . $product->name], 400);
            }
            $totalPrice += $product->price * $item['quantity'];
        }

        // Создаем заказ в транзакции
        $order = DB::transaction(function () use ($user, $items, &$totalPrice, $products) {
            // Создание заказа
            $order = Order::create([
                'order_number' => 'ORD-' . strtoupper(uniqid()),
                'user_id' => $user->id,
                'total_price' => $totalPrice,
                'status' => 'pending' // Статус по умолчанию
            ]);

            // Создание позиций заказа и уменьшение количества товара
            foreach ($items as $item) {
                $product = $products->firstWhere('id', $item['product_id']);
                $product->stock -= $item['quantity'];
                $product->save();

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                    'total_price' => $totalPrice,
                ]);
            }

            return $order;
        });

        // Возвращаем успешный ответ
        return response()->json([
            'order_number' => $order->order_number,
            'status' => $order->status,
            'total_price' => $order->total_price,
            'user_id' => $order->user_id,
        ], 201);
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