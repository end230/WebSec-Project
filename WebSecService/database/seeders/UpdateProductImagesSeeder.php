<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class UpdateProductImagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tea products with their corresponding images
        $productImages = [
            'Green Tea' => 'green-tea-cup-attractive-engaging-hd-wallpaper-background-photo_853645-72283.avif',
            'Black Tea' => 'Tea.jpg',
            'Oolong Tea' => 'oolong.jpg',
            'Peppermint Tea' => 'peppermint-tea-on-teacup-1417945.jpg',
            'Chamomile Tea' => 'tea-vintage-background-hand-drawn-sketch-illustration-menu-design_251616-1997.avif',
            'Earl Grey' => 'istockphoto-1174151804-612x612.jpg',
            'Pu-erh Tea' => 'Pu-erh-Tea3_pc.jpg',
            'Salted Caramel Oolong' => 'oolong-salted-caramel.png',
            'Jasmine Tea' => 'pexels-nipananlifestyle-com-625927-1581484.jpg'
        ];

        foreach ($productImages as $productName => $imagePath) {
            Product::where('name', 'like', "%$productName%")
                  ->update([
                      'photo' => $imagePath,
                      'main_photo' => $imagePath
                  ]);
        }
    }
} 