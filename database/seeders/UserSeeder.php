<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::insert([
            ['name' => 'Иван', 'email' => 'ivan@example.com', 'balance' => 200000],
            ['name' => 'Мария', 'email' => 'maria@example.com', 'balance' => 150000],
        ]);
    }
}
