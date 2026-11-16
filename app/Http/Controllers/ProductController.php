<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Admin & Pegawai: daftar produk (Read) + cari + filter kategori
    public function index(Request $request)
    {
        $products = Product::with('category')
            ->filter($request->only('q', 'category'))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $categories = Category::orderBy('nama_kategori')->get();

        return view('products.index', compact('products', 'categories'));
    }

    // Pembeli (dan semua user login): katalog produk, filter berdasarkan kategori
    public function catalog(Request $request)
    {
        $products = Product::with('category')
            ->filter($request->only('q', 'category'))
            ->where('stock', '>', 0)
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $categories = Category::orderBy('nama_kategori')->get();

        return view('products.catalog', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::orderBy('nama_kategori')->get();

        return view('products.create', compact('categories'));
    }

    // Validasi dilakukan otomatis oleh ProductRequest sebelum masuk method ini
    public function store(ProductRequest $request)
    {
        Product::create($request->validated());

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('nama_kategori')->get();

        return view('products.edit', compact('product', 'categories'));
    }

    public function update(ProductRequest $request, Product $product)
    {
        $product->update($request->validated());

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }

    // Update cepat stok (fitur pegawai: memantau & memperbarui stok)
    public function updateStock(Request $request, Product $product)
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

        return back()->with('success', "Stok {$product->name} berhasil diperbarui.");
    }
}
