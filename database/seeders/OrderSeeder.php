<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use Carbon\Carbon;

class OrderSeeder extends Seeder
{
    public function run()
    {
        Order::insert([
            [
                'user_id' => 1,
                'status' => 'pending',
                'total_price' => 160000,
                'order_number' => 'ORD-' . uniqid(), // Генерация уникального номера
            ],
            [
                'user_id' => 2,
                'status' => 'approved',
                'total_price' => 60000,
                'order_number' => 'ORD-' . uniqid(), // Генерация уникального номера
            ],
        ]);
    }
}
