@extends('layouts.app')

@section('title', 'Data Produk')

@section('content')
@php
    $categories = ['Makanan', 'Minuman', 'Kebutuhan Rumah'];
    $products = [
        ['name' => 'Indomie Goreng',     'description' => 'Mi instan goreng 85 gr',        'category' => 'Makanan',         'price' => 3500,  'stock' => 120],
        ['name' => 'Roti Tawar',         'description' => 'Roti tawar 400 gr',             'category' => 'Makanan',         'price' => 16000, 'stock' => 30],
        ['name' => 'Teh Botol 450 ml',   'description' => 'Teh manis dalam kemasan botol', 'category' => 'Minuman',         'price' => 5000,  'stock' => 80],
        ['name' => 'Air Mineral 600 ml', 'description' => 'Air mineral kemasan botol',     'category' => 'Minuman',         'price' => 3000,  'stock' => 4],
        ['name' => 'Sabun Cuci Piring',  'description' => 'Sabun cuci piring 800 ml',      'category' => 'Kebutuhan Rumah', 'price' => 14000, 'stock' => 40],
        ['name' => 'Deterjen Bubuk 1 kg','description' => 'Deterjen bubuk 1 kg',           'category' => 'Kebutuhan Rumah', 'price' => 22000, 'stock' => 25],
    ];
@endphp

<div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
    <h4 class="mb-0">Data Produk</h4>
    <a href="{{ route('products.create') }}" class="btn btn-primary">+ Tambah Produk</a>
</div>

{{-- Cari & filter --}}
<form method="GET" action="#" class="row g-2 mb-3" id="filter-form">
    <div class="col-md-5">
        <input type="text" id="search-input" class="form-control" placeholder="Cari nama produk...">
    </div>
    <div class="col-md-4">
        <select id="category-filter" class="form-select">
            <option value="">Semua kategori</option>
            @foreach ($categories as $category)
                <option value="{{ $category }}">{{ $category }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3 d-flex gap-2">
        <button type="button" id="btn-reset" class="btn btn-outline-secondary flex-fill">Reset</button>
    </div>
</form>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Nama</th>
                    <th>Kategori</th>
                    <th class="text-end">Harga</th>
                    <th style="width: 190px;">Stok</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                    <tr class="product-row" data-name="{{ strtolower($product['name']) }}" data-category="{{ $product['category'] }}">
                        <td class="row-number">{{ $loop->iteration }}</td>
                        <td>
                            <div class="fw-semibold">{{ $product['name'] }}</div>
                            <small class="text-muted">{{ $product['description'] }}</small>
                        </td>
                        <td><span class="badge bg-info text-dark">{{ $product['category'] }}</span></td>
                        <td class="text-end">Rp {{ number_format($product['price'], 0, ',', '.') }}</td>
                        <td>
                            <form action="#" method="POST" class="input-group input-group-sm form-stock">
                                <input type="number" min="0" value="{{ $product['stock'] }}"
                                       class="form-control {{ $product['stock'] <= 5 ? 'border-danger' : '' }}">
                                <button class="btn btn-outline-primary" title="Simpan stok">Ubah</button>
                            </form>
                            @if ($product['stock'] <= 5)
                                <small class="text-danger">Stok menipis</small>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('products.edit') }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="#" method="POST" class="d-inline form-delete" data-name="{{ $product['name'] }}">
                                <button class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                <tr id="empty-row" style="display:none;">
                    <td colspan="6" class="text-center text-muted py-4">Belum ada data produk.</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

{{-- Pagination statis (tampilan saja) --}}
<nav class="mt-3">
    <ul class="pagination">
        <li class="page-item disabled"><span class="page-link">&laquo;</span></li>
        <li class="page-item active"><span class="page-link">1</span></li>
        <li class="page-item"><a class="page-link" href="#">2</a></li>
        <li class="page-item"><a class="page-link" href="#">&raquo;</a></li>
    </ul>
</nav>
@endsection

@push('scripts')
<script>
    // Filter nama + kategori langsung di browser
    function applyFilter() {
        const q = $('#search-input').val().toLowerCase();
        const cat = $('#category-filter').val();
        let visible = 0;

        $('.product-row').each(function () {
            const match = $(this).data('name').includes(q) && (!cat || $(this).data('category') === cat);
            $(this).toggle(match);
            if (match) {
                visible++;
                $(this).find('.row-number').text(visible);
            }
        });

        $('#empty-row').toggle(visible === 0);
    }

    $('#search-input').on('input', applyFilter);
    $('#category-filter').on('change', applyFilter);
    $('#btn-reset').on('click', function () {
        $('#search-input').val('');
        $('#category-filter').val('');
        applyFilter();
    });
    $('#filter-form').on('submit', function (e) { e.preventDefault(); });

    // Konfirmasi hapus (hapus baris di tampilan saja)
    $('.form-delete').on('submit', function (e) {
        e.preventDefault();
        const name = $(this).data('name');
        if (confirm('Yakin ingin menghapus produk "' + name + '"?')) {
            $(this).closest('tr').remove();
            applyFilter();
        }
    });

    // Simpan stok (simulasi)
    $('.form-stock').on('submit', function (e) {
        e.preventDefault();
        alert('Stok diperbarui (simulasi tampilan).');
    });
</script>
@endpush