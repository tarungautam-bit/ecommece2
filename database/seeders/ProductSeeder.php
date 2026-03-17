<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $products = [
            [
                'name' => 'Product 1',
                'description' => 'Description for Product 1',
                'price' => 19.99,
                'image' => 'products/product1.jpg',
            ],
            [
                'name' => 'Product 2',
                'description' => 'Description for Product 2',
                'price' => 29.99,
                'image' => 'products/product2.jpg',
            ],
            [
                'name' => 'Product 3',
                'description' => 'Description for Product 3',
                'price' => 39.99,
                'image' => 'products/product3.jpg',
            ],
        ];

        foreach ($products as $product) {
            DB::table('products')->insert([
                'name' => $product['name'],
                'description' => $product['description'],
                'price' => $product['price'],
                'image' => $product['image'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->copyProductImages();
    }

    private function copyProductImages()
    {
        $sourcePath = base_path('public/images/products');
        $destinationPath = storage_path('app/public/products');

        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        $images = [
            'product1.jpg',
            'product2.jpg',
            'product3.jpg',
        ];

        foreach ($images as $image) {
            if (file_exists($sourcePath . '/' . $image)) {
                copy($sourcePath . '/' . $image, $destinationPath . '/' . $image);
            }
        }
    }
}
