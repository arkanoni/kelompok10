<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['Indomie Goreng',      'Mi instan goreng 85 gr',        'Makanan',         3500,  120],
            ['Roti Tawar',          'Roti tawar 400 gr',             'Makanan',         16000, 30],
            ['Teh Botol 450 ml',    'Teh manis dalam kemasan botol', 'Minuman',         5000,  80],
            ['Air Mineral 600 ml',  'Air mineral kemasan botol',     'Minuman',         3000,  4],
            ['Sabun Cuci Piring',   'Sabun cuci piring 800 ml',      'Kebutuhan Rumah', 14000, 40],
            ['Deterjen Bubuk 1 kg', 'Deterjen bubuk 1 kg',           'Kebutuhan Rumah', 22000, 25],
        ];

        foreach ($items as [$name, $desc, $category, $price, $stock]) {
            Product::updateOrCreate(
                ['name' => $name],
                [
                    'category_id' => Category::where('nama_kategori', $category)->value('id'),
                    'description' => $desc,
                    'price'       => $price,
                    'stock'       => $stock,
                ]
            );
        }
    }
}
