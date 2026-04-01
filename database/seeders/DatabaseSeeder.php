<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed User
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        // 2. Seed Products
        $product1 = Product::create(['name' => 'Laptop ASUS', 'price' => 15000000]);
        $product2 = Product::create(['name' => 'Mouse Logitech', 'price' => 250000]);
        $product3 = Product::create(['name' => 'Keyboard Mechanical', 'price' => 850000]);

        // 3. Seed Purchases & Items
        // Purchase 1
        $purchase1 = Purchase::create([
            'date' => now()->format('Y-m-d'),
            'total_price' => 15250000
        ]);

        PurchaseItem::create([
            'purchase_id' => $purchase1->id,
            'product_id' => $product1->id,
            'qty' => 1,
            'price' => $product1->price
        ]);

        PurchaseItem::create([
            'purchase_id' => $purchase1->id,
            'product_id' => $product2->id,
            'qty' => 1,
            'price' => $product2->price
        ]);

        // Purchase 2
        $purchase2 = Purchase::create([
            'date' => now()->subDay()->format('Y-m-d'),
            'total_price' => 1700000
        ]);

        PurchaseItem::create([
            'purchase_id' => $purchase2->id,
            'product_id' => $product3->id,
            'qty' => 2,
            'price' => $product3->price
        ]);
    }
}
