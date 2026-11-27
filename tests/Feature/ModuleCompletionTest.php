<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use DOMDocument;
use DOMXPath;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;
use LogicException;
use Tests\TestCase;

class ModuleCompletionTest extends TestCase
{
    use RefreshDatabase;

    protected function beforeRefreshingDatabase(): void
    {
        if (config('database.default') !== 'sqlite'
            || config('database.connections.sqlite.database') !== ':memory:'
            || config('database.connections.sqlite.url')) {
            throw new LogicException('ModuleCompletionTest hanya boleh memakai SQLite :memory:.');
        }
    }

    public function test_admin_category_forms_use_the_existing_schema_and_complete_crud(): void
    {
        $this->signInAs('admin');
        $this->get(route('categories.index'))->assertOk()->assertSee('Belum ada data kategori.');
        $this->get(route('categories.create'))->assertOk()
            ->assertSee('name="nama_kategori"', false)
            ->assertSee('action="'.route('categories.store').'"', false);

        $this->post(route('categories.store'), ['nama_kategori' => 'Minuman Uji'])
            ->assertRedirect(route('categories.index'))->assertSessionHasNoErrors();
        $category = Category::sole();
        $this->assertSame('Minuman Uji', $category->nama_kategori);
        $this->assertSame('minuman-uji', $category->slug);

        $this->get(route('categories.edit', $category))->assertOk()
            ->assertSee('Minuman Uji')->assertSee('name="_method" value="PUT"', false);
        $this->put(route('categories.update', $category), ['nama_kategori' => 'Minuman Diperbarui'])
            ->assertRedirect(route('categories.index'))->assertSessionHasNoErrors();
        $this->get(route('categories.index'))->assertOk()
            ->assertSee('Minuman Diperbarui')->assertSee('minuman-diperbarui');
        $this->assertSame('Minuman Diperbarui', $category->refresh()->nama_kategori);

        $this->delete(route('categories.destroy', $category))
            ->assertRedirect(route('categories.index'));
        $this->assertDatabaseCount('categories', 0);
    }

    public function test_product_table_search_and_category_filter_preserve_pagination(): void
    {
        $category = $this->searchInventory();
        $this->signInAs('pegawai');
        $response = $this->get(route('products.index', ['q' => 'Kopi', 'category' => $category->id]))
            ->assertOk()->assertSee('Minuman Pilihan')->assertDontSee('Teh Tidak Cocok')
            ->assertDontSee('Kopi Kategori Lain');
        $products = $response->viewData('products');
        $this->assertInstanceOf(LengthAwarePaginator::class, $products);
        $this->assertSame(13, $products->total());
        $this->assertCount(10, $products->items());
        $this->assertPaginationFilter($products->nextPageUrl(), $category);

        $next = $this->get($products->nextPageUrl())->assertOk();
        $this->assertCount(3, $next->viewData('products')->items());
        $this->assertSame(11, $next->viewData('products')->firstItem());
        $this->get('/products/stock')->assertRedirect('/products');
    }

    public function test_buyer_catalog_displays_actual_category_labels_and_filters_across_pages(): void
    {
        $category = $this->searchInventory();
        $this->signInAs('user');
        $response = $this->get(route('products.catalog', ['q' => 'Kopi', 'category' => $category->id]))
            ->assertOk()->assertSee('Katalog Produk')->assertDontSee('Teh Tidak Cocok')
            ->assertDontSee('Kopi Kategori Lain')->assertDontSee('Tambah Produk');
        $products = $response->viewData('products');
        $this->assertInstanceOf(LengthAwarePaginator::class, $products);
        $this->assertSame(13, $products->total());
        $this->assertCount(8, $products->items());
        $this->assertPaginationFilter($products->nextPageUrl(), $category);

        $document = new DOMDocument;
        $previous = libxml_use_internal_errors(true);
        try {
            $document->loadHTML($response->getContent());
            $badges = (new DOMXPath($document))->query('//span[contains(concat(" ", normalize-space(@class), " "), " badge ")]');
            $this->assertSame(8, $badges->length);
            foreach ($badges as $badge) {
                $this->assertSame('Minuman Pilihan', trim($badge->textContent));
            }
        } finally {
            libxml_clear_errors();
            libxml_use_internal_errors($previous);
        }

        $next = $this->get($products->nextPageUrl())->assertOk();
        $this->assertCount(5, $next->viewData('products')->items());
        $this->get(route('products.catalog', ['q' => 'Nama Tidak Ada']))
            ->assertOk()->assertSee('Produk tidak ditemukan.');
    }

    private function signInAs(string $role): void
    {
        $this->actingAs(User::factory()->create(['role' => $role]));
    }

    private function category(string $name, string $slug): Category
    {
        return Category::create(['nama_kategori' => $name, 'slug' => $slug]);
    }

    private function product(Category $category, string $name, string $price, int $stock): Product
    {
        return Product::create($this->productInput($category, $name, $price, $stock));
    }

    private function productInput(Category $category, string $name, string $price, int $stock): array
    {
        return [
            'category_id' => $category->id,
            'name' => $name,
            'description' => 'Fixture uji integrasi modul.',
            'price' => $price,
            'stock' => $stock,
        ];
    }

    private function searchInventory(): Category
    {
        $category = $this->category('Minuman Pilihan', 'minuman-pilihan');
        for ($index = 1; $index <= 13; $index++) {
            $this->product($category, sprintf('Kopi Pilihan %02d', $index), '12.50', 10)
                ->forceFill(['created_at' => sprintf('2026-11-01 10:00:%02d', $index)])->save();
        }
        $this->product($category, 'Teh Tidak Cocok', '5.00', 10);
        $other = $this->category('Kategori Lain', 'kategori-lain');
        $this->product($other, 'Kopi Kategori Lain', '7.00', 10);

        return $category;
    }

    private function assertPaginationFilter(string $url, Category $category): void
    {
        parse_str(parse_url($url, PHP_URL_QUERY), $query);
        $this->assertSame('Kopi', $query['q']);
        $this->assertSame((string) $category->id, $query['category']);
        $this->assertSame('2', $query['page']);
    }
}
