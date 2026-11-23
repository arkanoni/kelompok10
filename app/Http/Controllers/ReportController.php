<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ReportController extends Controller
{
    public function inventory()
    {
        $products = Product::with('category')->orderBy('name')->get();
        $totalStock = $products->sum('stock');
        $totalValue = $products->sum(fn ($p)=> $p->price * $p->stock);

        return view('reports.inventory', compact('products', 'totalStock', 'totalValue'));
    }

    // Export CSV
     public function export()
    {
        $products = Product::with('category')->orderBy('name')->get();

        return response()->streamDownload(function () use ($products) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Produk', 'Kategori', 'Harga', 'Stok', 'Nilai']);
            foreach ($products as $p) {
                fputcsv($out, [$p->name, $p->category->name ?? '-', $p->price, $p->stock, $p->price * $p->stock]);
            }
            fclose($out);
        }, 'laporan-inventaris.csv', ['Content-Type' => 'text/csv']);
    }
}
