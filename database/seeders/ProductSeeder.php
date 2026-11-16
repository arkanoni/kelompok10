<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'Makanan' => [
                ['Indomie Goreng', 'Mi instan goreng 85 gr', 3500, 120],
                ['Roti Tawar', 'Roti tawar 400 gr', 16000, 30],
            ],
            'Minuman' => [
                ['Teh Botol 450 ml', 'Teh manis dalam kemasan botol', 5000, 80],
                ['Air Mineral 600 ml', 'Air mineral kemasan botol', 3000, 200],
            ],
            'Kebutuhan Rumah' => [
                ['Sabun Cuci Piring', 'Sabun cuci piring 800 ml', 14000, 40],
                ['Deterjen Bubuk 1 kg', 'Deterjen bubuk 1 kg', 22000, 25],
            ],
        ];

        foreach ($data as $categoryName => $items) {
            // firstOrCreate supaya seeder tetap jalan walau CategorySeeder belum ada
            $category = Category::firstOrCreate(
                ['slug' => Str::slug($categoryName)],
                ['nama_kategori' => $categoryName]
            );

            foreach ($items as [$name, $description, $price, $stock]) {
                Product::updateOrCreate(
                    ['name' => $name],
                    [
                        'category_id' => $category->id,
                        'description' => $description,
                        'price'       => $price,
                        'stock'       => $stock,
                    ]
                );
            }
        }
    }
}
