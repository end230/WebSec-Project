<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'code' => 'LAPTOP-001',
                'name' => 'MacBook Pro 16"',
                'model' => 'Apple MacBook Pro 2023',
                'description' => 'Powerful laptop with M2 Pro chip, 16GB RAM, 512GB SSD',
                'price' => 2499.99,
                'stock_quantity' => 50,
                'photo' => 'macbook.jpg'
            ],
            [
                'code' => 'PHONE-001',
                'name' => 'iPhone 15 Pro',
                'model' => 'Apple iPhone 15 Pro 256GB',
                'description' => 'Latest iPhone with A17 Pro chip, 256GB storage, and amazing camera system',
                'price' => 1099.99,
                'stock_quantity' => 100,
                'photo' => 'iphone.jpg'
            ],
            [
                'code' => 'TABLET-001',
                'name' => 'iPad Air',
                'model' => 'Apple iPad Air 2022',
                'description' => 'Sleek tablet with M1 chip, 10.9-inch display, and 256GB storage',
                'price' => 749.99,
                'stock_quantity' => 75,
                'photo' => 'ipad.jpg'
            ],
            [
                'code' => 'LAPTOP-002',
                'name' => 'Dell XPS 15',
                'model' => 'Dell XPS 15 9530',
                'description' => 'Premium Windows laptop with 13th Gen Intel Core i7, 16GB RAM, 1TB SSD',
                'price' => 1899.99,
                'stock_quantity' => 30,
                'photo' => 'dell.jpg'
            ],
            [
                'code' => 'ACCESSORY-001',
                'name' => 'AirPods Pro',
                'model' => 'Apple AirPods Pro 2nd Gen',
                'description' => 'Wireless earbuds with active noise cancellation and spatial audio',
                'price' => 249.99,
                'stock_quantity' => 150,
                'photo' => 'airpods.jpg'
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
