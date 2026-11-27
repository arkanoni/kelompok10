<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Database\Seeders\DemoInventorySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use LogicException;
use RuntimeException;
use Tests\TestCase;

class InventoryCompletionTest extends TestCase
{
    use RefreshDatabase;

    protected function beforeRefreshingDatabase(): void
    {
        if (config('database.default') !== 'sqlite'
            || config('database.connections.sqlite.database') !== ':memory:'
            || config('database.connections.sqlite.url')) {
            throw new LogicException('InventoryCompletionTest hanya boleh memakai SQLite :memory:.');
        }
    }

    public function test_empty_inventory_is_seeded_and_report_displays_the_expected_totals(): void
    {
        $this->artisan('db:seed', ['--class' => DemoInventorySeeder::class])
            ->expectsOutputToContain('Inventaris demo berhasil ditambahkan.')
            ->assertSuccessful();

        $this->assertDatabaseCount('categories', 3);
        $this->assertDatabaseCount('products', 6);
        $this->assertDatabaseCount('users', 0);
        $this->assertSame(299, (int) Product::sum('stock'));
        $this->assertSame(1, Product::where('stock', '<=', Product::LOW_STOCK)->count());

        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin)->get(route('reports.inventory'))->assertOk()
            ->assertViewHas('products', fn ($products) => $products->count() === 6)
            ->assertViewHas('totalStock', 299)
            ->assertViewHas('totalValue', '2422000.00')
            ->assertViewHas('lowStock', 1)
            ->assertSee('2.422.000,00');
    }

    public function test_rerunning_inventory_seed_preserves_modified_products_and_categories(): void
    {
        $this->freezeTime();
        $this->seed(DemoInventorySeeder::class);
        $this->travel(1)->minutes();
        Product::where('name', 'Air Mineral 600 ml')->firstOrFail()->update([
            'stock' => 2,
            'price' => '7777.25',
            'description' => 'Perubahan pemilik toko yang harus dipertahankan.',
        ]);
        Category::where('nama_kategori', 'Minuman')->firstOrFail()->update([
            'slug' => 'kategori-minuman-pilihan',
        ]);
        $productsBefore = $this->productsSnapshot();
        $categoriesBefore = $this->categoriesSnapshot();
        $this->travel(1)->minutes();

        $this->artisan('db:seed', ['--class' => DemoInventorySeeder::class])
            ->expectsOutputToContain('Pengisian inventaris demo dilewati')
            ->assertSuccessful();

        $this->assertSame($productsBefore, $this->productsSnapshot());
        $this->assertSame($categoriesBefore, $this->categoriesSnapshot());
    }

    public function test_any_existing_product_skips_all_demo_products_and_missing_demo_categories(): void
    {
        $category = Category::create(['nama_kategori' => 'Kategori Asli', 'slug' => 'kategori-asli']);
        Product::create([
            'category_id' => $category->id,
            'name' => 'Produk Asli',
            'description' => 'Data toko, bukan demo.',
            'price' => '15300.50',
            'stock' => 11,
        ]);
        $productsBefore = $this->productsSnapshot();
        $categoriesBefore = $this->categoriesSnapshot();

        $this->artisan('db:seed', ['--class' => DemoInventorySeeder::class])
            ->expectsOutputToContain('Data produk sudah tersedia. Pengisian inventaris demo dilewati')
            ->assertSuccessful();

        $this->assertSame($productsBefore, $this->productsSnapshot());
        $this->assertSame($categoriesBefore, $this->categoriesSnapshot());
        $this->assertSame(0, Category::whereIn('nama_kategori', ['Makanan', 'Minuman', 'Kebutuhan Rumah'])->count());
    }

    public function test_existing_named_categories_are_reused_and_unrelated_slug_collisions_are_preserved(): void
    {
        $existingFood = Category::create(['nama_kategori' => 'Makanan', 'slug' => 'makanan-pilihan-pemilik']);
        $occupied = Category::create(['nama_kategori' => 'Kategori Lama', 'slug' => 'minuman']);
        $occupiedSuffix = Category::create(['nama_kategori' => 'Kategori Lama Lain', 'slug' => 'minuman-2']);
        $originals = collect([$existingFood, $occupied, $occupiedSuffix])
            ->mapWithKeys(fn (Category $category) => [$category->id => $category->refresh()->getAttributes()])->all();

        $this->seed(DemoInventorySeeder::class);

        $this->assertDatabaseCount('categories', 5);
        $this->assertDatabaseCount('products', 6);
        foreach ($originals as $id => $attributes) {
            $this->assertSame($attributes, Category::findOrFail($id)->getAttributes());
        }
        $this->assertSame(1, Category::where('nama_kategori', 'Makanan')->count());
        $this->assertSame(2, Product::where('category_id', $existingFood->id)->count());
        $newDrink = Category::where('nama_kategori', 'Minuman')->firstOrFail();
        $this->assertSame('minuman-3', $newDrink->slug);
        $this->assertSame(2, Product::where('category_id', $newDrink->id)->count());
        $this->assertSame(0, Product::whereIn('category_id', [$occupied->id, $occupiedSuffix->id])->count());
    }

    public function test_inventory_seed_does_not_create_reset_or_modify_any_users(): void
    {
        foreach (['admin', 'pegawai', 'user'] as $role) {
            User::factory()->create([
                'email' => $role.'@existing.example.test',
                'role' => $role,
                'password' => 'PasswordPemilik123!',
            ]);
        }
        $before = User::orderBy('id')->get()->map(fn (User $user) => $user->getAttributes())->all();

        $this->seed(DemoInventorySeeder::class);
        $this->seed(DemoInventorySeeder::class);

        $this->assertSame($before, User::orderBy('id')->get()->map(fn (User $user) => $user->getAttributes())->all());
        $this->assertDatabaseCount('users', 3);
    }

    public function test_inventory_demo_is_rejected_outside_local_and_testing_without_writes(): void
    {
        $this->app->instance('env', 'production');

        try {
            app(DemoInventorySeeder::class)->run();
            $this->fail('Production must reject demo inventory seeding.');
        } catch (LogicException $exception) {
            $this->assertSame('DemoInventorySeeder hanya untuk APP_ENV=local atau testing.', $exception->getMessage());
        } finally {
            $this->app->instance('env', 'testing');
        }

        $this->assertDatabaseCount('categories', 0);
        $this->assertDatabaseCount('products', 0);
        $this->assertDatabaseCount('users', 0);
    }

    public function test_a_product_insert_failure_rolls_back_new_categories_and_preceding_products(): void
    {
        $creatingEvent = 'eloquent.creating: '.Product::class;
        $attempts = 0;
        Event::listen($creatingEvent, function () use (&$attempts): void {
            if (++$attempts === 2) {
                throw new RuntimeException('Simulated second product insert failure.');
            }
        });

        try {
            app(DemoInventorySeeder::class)->run();
            $this->fail('The deliberately failed product insert must abort seeding.');
        } catch (RuntimeException $exception) {
            $this->assertSame('Simulated second product insert failure.', $exception->getMessage());
        } finally {
            Event::forget($creatingEvent);
        }

        $this->assertSame(2, $attempts);
        $this->assertDatabaseCount('categories', 0);
        $this->assertDatabaseCount('products', 0);
        $this->assertDatabaseCount('users', 0);
    }

    private function categoriesSnapshot(): array
    {
        return Category::orderBy('id')->get()->map(fn (Category $category) => $category->getAttributes())->all();
    }

    private function productsSnapshot(): array
    {
        return Product::orderBy('id')->get()->map(fn (Product $product) => $product->getAttributes())->all();
    }
}
