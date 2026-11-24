<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    // READ: tabel data produk (cari + filter kategori + pagination)
    public function index(Request $request): View
    {
        $products = Product::with('category')
            ->filter($request->only('q', 'category'))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $categories = Category::orderBy('nama_kategori')->get();

        return view('products.index', compact('products', 'categories'));
    }

    // READ: katalog kartu produk (tampilan pembeli)
    public function catalog(Request $request): View
    {
        $products = Product::with('category')
            ->filter($request->only('q', 'category'))
            ->latest()
            ->paginate(8)
            ->withQueryString();

        $categories = Category::orderBy('name')->get();

        return view('products.catalog', compact('products', 'categories'));
    }

    // CREATE: form
    public function create(): View
    {
        $categories = Category::orderBy('name')->get();

        return view('products.create', compact('categories'));
    }

    // CREATE: simpan (validasi di ProductRequest)
    public function store(ProductRequest $request): RedirectResponse
    {
        Product::create($request->validated());

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    // UPDATE: form (route model binding)
    public function edit(Product $product): View
    {
        $categories = Category::orderBy('name')->get();

        return view('products.edit', compact('product', 'categories'));
    }

    // UPDATE: simpan perubahan
    public function update(ProductRequest $request, Product $product): RedirectResponse
    {
        $product->update($request->validated());

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    // UPDATE cepat: ubah stok langsung dari tabel
    public function updateStock(Request $request, Product $product): RedirectResponse
    {
        $data = $request->validate(
            ['stock' => ['required', 'integer', 'min:0']],
            [
                'stock.required' => 'Stok wajib diisi.',
                'stock.integer'  => 'Stok harus berupa bilangan bulat.',
                'stock.min'      => 'Stok tidak boleh negatif.',
            ]
        );

        $product->update($data);

        return back()->with('success', "Stok \"{$product->name}\" diperbarui menjadi {$product->stock}.");
    }

    // DELETE
    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }
}
