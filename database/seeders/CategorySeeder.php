<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Lengkapi kategori yang diperlukan ProductSeeder tanpa mengubah kategori lama.
     */
    public function run(): void
    {
        foreach (['Makanan', 'Minuman', 'Kebutuhan Rumah'] as $name) {
            if (Category::where('nama_kategori', $name)->exists()) {
                continue;
            }

            $baseSlug = Str::slug($name);
            $slug = $baseSlug;
            $suffix = 2;

            while (Category::where('slug', $slug)->exists()) {
                $slug = $baseSlug.'-'.$suffix++;
            }

            Category::create(['nama_kategori' => $name, 'slug' => $slug]);
        }
    }
}
