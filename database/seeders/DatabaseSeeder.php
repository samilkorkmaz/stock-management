<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Shop;
use App\Models\Item;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create demo shop
        Shop::create([
            'name' => 'Demo Shop',
            'email' => 'shop@example.com',
            'password' => Hash::make('password'),
        ]);

        // Create some items
        Item::create([
            'name' => 'Product A',
            'description' => 'Description for Product A',
            'quantity' => 100,
        ]);

        Item::create([
            'name' => 'Product B',
            'description' => 'Description for Product B',
            'quantity' => 150,
        ]);
    }
}
