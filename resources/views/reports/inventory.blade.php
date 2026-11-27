@extends('layouts.app')

@section('title', 'Laporan Inventaris')

@section('content')
@php $role = auth()->user()->role; @endphp
{{-- Variabel: $products, $categories, $recap, $totalStock, $totalValue, $lowStock --}}

<style>
    @media print {
        nav.navbar,
        .report-inventory .d-print-none {
            display: none !important;
        }

        .report-inventory .card {
            box-shadow: none !important;
        }

        .report-inventory .card-body {
            padding: 0.5rem;
        }
    }
</style>

<div class="report-inventory">
<div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
    <h4 class="mb-0">Laporan Inventaris</h4>

    {{-- Cetak & export hanya untuk admin --}}
    @if ($role === 'admin')
        <div class="d-flex gap-2 d-print-none">
            <button type="button" class="btn btn-outline-primary" onclick="window.print()">Cetak</button>
            <a href="{{ route('reports.export', request()->only('category')) }}" class="btn btn-success">Export CSV (Excel)</a>
        </div>
    @endif
</div>

@if ($errors->any())
    <div class="alert alert-danger d-print-none" role="alert">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="GET" action="{{ route('reports.inventory') }}" class="row g-2 mb-3 d-print-none" id="filter-form">
    <div class="col-md-5">
        <select name="category" id="category-filter" class="form-select" aria-label="Filter kategori">
            <option value="">Semua kategori</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected(request('category') == $category->id)>{{ $category->nama_kategori }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4 d-flex gap-2">
        <button type="submit" class="btn btn-outline-primary">Tampilkan</button>
        <a href="{{ route('reports.inventory') }}" class="btn btn-outline-secondary">Reset</a>
    </div>
</form>

<div class="row g-3 mb-3">
    <div class="col-6 col-lg-3"><div class="card shadow-sm"><div class="card-body">
        <div class="text-muted small">Jenis produk</div><div class="fs-4 fw-bold">{{ $products->count() }}</div>
    </div></div></div>
    <div class="col-6 col-lg-3"><div class="card shadow-sm"><div class="card-body">
        <div class="text-muted small">Total stok</div><div class="fs-4 fw-bold">{{ $totalStock }}</div>
    </div></div></div>
    <div class="col-6 col-lg-3"><div class="card shadow-sm"><div class="card-body">
        <div class="text-muted small">Nilai inventaris</div><div class="fs-4 fw-bold">Rp {{ \App\Support\Money::format($totalValue) }}</div>
    </div></div></div>
    <div class="col-6 col-lg-3"><div class="card shadow-sm"><div class="card-body">
        <div class="text-muted small">Stok menipis</div><div class="fs-4 fw-bold {{ $lowStock ? 'text-danger' : '' }}">{{ $lowStock }}</div>
    </div></div></div>
</div>

<div class="card shadow-sm mb-3">
    <div class="card-header fw-semibold">Rekap stok per kategori</div>
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr><th>Kategori</th><th class="text-end">Jenis produk</th><th class="text-end">Total stok</th><th class="text-end">Nilai</th></tr>
            </thead>
            <tbody>
                @forelse ($recap as $r)
                    <tr>
                        <td>{{ $r['nama'] }}</td>
                        <td class="text-end">{{ $r['jenis'] }}</td>
                        <td class="text-end">{{ $r['stok'] }}</td>
                        <td class="text-end">Rp {{ \App\Support\Money::format($r['nilai']) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-muted py-4">Belum ada data.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header fw-semibold">Data produk</div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Produk</th><th>Kategori</th>
                    <th class="text-end">Harga</th><th class="text-end">Stok</th><th class="text-end">Nilai</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $p)
                    <tr>
                        <td>{{ $p->name }}</td>
                        <td><span class="badge bg-info text-dark">{{ $p->category->nama_kategori ?? '-' }}</span></td>
                        <td class="text-end">Rp {{ \App\Support\Money::format($p->price) }}</td>
                        <td class="text-end">
                            {{ $p->stock }}
                            @if ($p->isLowStock()) <span class="badge bg-danger">menipis</span> @endif
                        </td>
                        <td class="text-end">Rp {{ \App\Support\Money::format(\App\Support\Money::multiply($p->price, $p->stock)) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">Belum ada data produk.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
</div>
@endsection

