<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    private function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|integer|min:0',
            'stock' => 'required|integer|min:0',
        ];
    }

    public function index(Request $request)
    {
        $products = Product::with('category')
            ->when($request->category, fn ($q, $c) => $q->where('category_id', $c))
            ->latest()->get();
        $categories = Category::orderBy('name')->get();

        return view('products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        Product::create($request->validate($this->rules()));

        return redirect('/products')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $product->update($request->validate($this->rules()));

        return redirect('/products')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        try {
            $product->delete();
        } catch (QueryException $e) {
            // produk sudah pernah dipesan (terikat order_items)
            return back()->withErrors(['hapus' => 'Produk sudah ada di riwayat pesanan, tidak bisa dihapus.']);
        }

        return redirect('/products')->with('success', 'Produk berhasil dihapus.');
    }

    public function stock()
    {
        $products = Product::with('category')->orderBy('name')->get();
        return view('products.stock', compact('products'));
    }

    public function updateStock(Request $request, Product $product)
    {
        $data = $request->validate(['stock' => 'required|integer|min:0']);
        $product->update($data);

        return back()->with('success', 'Stok ' . $product->name . ' diperbarui.');
    }
}
