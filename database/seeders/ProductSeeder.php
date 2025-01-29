<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        Product::insert([
            ['name' => 'Ноутбук', 'description' => 'Игровой ноутбук', 'price' => 100000, 'stock' => 10],
            ['name' => 'Смартфон', 'description' => 'Флагманский смартфон', 'price' => 60000, 'stock' => 20],
            ['name' => 'Планшет', 'description' => 'Легкий планшет', 'price' => 40000, 'stock' => 15],
        ]);
    }
}