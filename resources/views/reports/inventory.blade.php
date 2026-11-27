@extends('layouts.app')@section('title','Laporan inventaris')@section('content')
{{-- Variabel: $products (name, category->name, price, stock), $totalStock, $totalValue --}}
<h1>Laporan inventaris</h1>
<div class="noprint" style="margin-bottom:16px"><button class="btn" onclick="window.print()">Cetak</button> <a class="btn gray" href="/reports/inventory/export">Export Excel</a></div>
<div class="grid" style="margin-bottom:16px"><div class="card">Jenis produk<h2>{{ count($products ?? []) }}</h2></div><div class="card">Total stok<h2>{{ $totalStock ?? 0 }}</h2></div><div class="card">Nilai inventaris<h2>Rp {{ number_format($totalValue ?? 0, 0, ',', '.') }}</h2></div></div>
<div class="card"><table><tr><th>Produk</th><th>Kategori</th><th>Harga</th><th>Stok</th><th>Nilai</th></tr>
@forelse(($products ?? []) as $p)<tr><td>{{ $p->name }}</td><td>{{ $p->category->name ?? '-' }}</td><td>Rp {{ number_format($p->price, 0, ',', '.') }}</td><td>{{ $p->stock }}</td><td>Rp {{ number_format($p->price * $p->stock, 0, ',', '.') }}</td></tr>
@empty<tr><td colspan="5">Belum ada data.</td></tr>@endforelse
</table></div>@endsection
