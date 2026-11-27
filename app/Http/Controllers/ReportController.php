<?php

namespace App\Http\Controllers;

use App\Http\Requests\InventoryReportRequest;
use App\Models\Category;
use App\Models\Product;
use App\Support\Money;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    /** @return Collection<int, Product> */
    private function products(InventoryReportRequest $request): Collection
    {
        return Product::with('category')
            ->filter($request->safe()->only('category'))
            ->orderBy('name')
            ->get();
    }

    public function inventory(InventoryReportRequest $request): View
    {
        $products = $this->products($request);
        $categories = Category::orderBy('nama_kategori')->get();

        $recap = $products
            ->groupBy('category_id')
            ->map(fn (Collection $items): array => [
                'nama' => $items->first()->category?->nama_kategori ?? 'Tanpa kategori',
                'jenis' => $items->count(),
                'stok' => $items->sum('stock'),
                'nilai' => Money::sum($items->map(fn (Product $product): string => Money::multiply($product->price, $product->stock))),
            ]);

        $totalStock = $products->sum('stock');
        $totalValue = Money::sum($products->map(fn (Product $product): string => Money::multiply($product->price, $product->stock)));
        $lowStock = $products->filter(fn (Product $product): bool => $product->isLowStock())->count();

        return view('reports.inventory', compact(
            'products', 'categories', 'recap', 'totalStock', 'totalValue', 'lowStock',
        ));
    }

    public function export(InventoryReportRequest $request): StreamedResponse
    {
        $products = $this->products($request);

        return response()->streamDownload(function () use ($products): void {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF");
            fputcsv($out, ['Produk', 'Kategori', 'Harga', 'Stok', 'Nilai'], ',', '"', '');

            foreach ($products as $product) {
                fputcsv($out, [
                    $this->csvText($product->name),
                    $this->csvText($product->category?->nama_kategori ?? '-'),
                    $product->price,
                    $product->stock,
                    Money::multiply($product->price, $product->stock),
                ], ',', '"', '');
            }

            fclose($out);
        }, 'laporan-inventaris.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    private function csvText(string $value): string
    {
        return preg_match('/^[\s\x{FEFF}]*[=+\-@]/u', $value) === 1 ? "'".$value : $value;
    }
}
