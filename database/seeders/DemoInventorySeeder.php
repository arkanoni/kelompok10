<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use LogicException;

class DemoInventorySeeder extends Seeder
{
    /**
     * Isi inventaris demo hanya ketika belum ada produk di lingkungan lokal.
     */
    public function run(): void
    {
        if (! app()->environment('local', 'testing')) {
            throw new LogicException('DemoInventorySeeder hanya untuk APP_ENV=local atau testing.');
        }

        $seeded = DB::transaction(function (): bool {
            if (Product::query()->exists()) {
                return false;
            }

            $this->call([CategorySeeder::class, ProductSeeder::class]);

            return true;
        });

        if (! $seeded) {
            $this->command?->warn('Data produk sudah tersedia. Pengisian inventaris demo dilewati agar stok dan data lama tetap aman.');

            return;
        }

        $this->command?->info('Inventaris demo berhasil ditambahkan.');
    }
}
