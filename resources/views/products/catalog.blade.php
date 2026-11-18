@extends('layouts.app')

@section('title', 'Katalog Produk')

@section('content')
@php
    $categories = ['Makanan', 'Minuman', 'Kebutuhan Rumah'];
    $products = [
        ['name' => 'Indomie Goreng',     'description' => 'Mi instan goreng 85 gr',        'category' => 'Makanan',         'price' => 3500,  'stock' => 120],
        ['name' => 'Roti Tawar',         'description' => 'Roti tawar 400 gr',             'category' => 'Makanan',         'price' => 16000, 'stock' => 30],
        ['name' => 'Teh Botol 450 ml',   'description' => 'Teh manis dalam kemasan botol', 'category' => 'Minuman',         'price' => 5000,  'stock' => 80],
        ['name' => 'Air Mineral 600 ml', 'description' => 'Air mineral kemasan botol',     'category' => 'Minuman',         'price' => 3000,  'stock' => 200],
        ['name' => 'Sabun Cuci Piring',  'description' => 'Sabun cuci piring 800 ml',      'category' => 'Kebutuhan Rumah', 'price' => 14000, 'stock' => 40],
        ['name' => 'Deterjen Bubuk 1 kg','description' => 'Deterjen bubuk 1 kg',           'category' => 'Kebutuhan Rumah', 'price' => 22000, 'stock' => 25],
    ];
@endphp

<h4 class="mb-3">Katalog Produk</h4>

<form method="GET" action="#" class="row g-2 mb-4" id="filter-form">
    <div class="col-md-6">
        <input type="text" id="search-input" class="form-control" placeholder="Cari produk...">
    </div>
    <div class="col-md-4">
        <select id="category-filter" class="form-select">
            <option value="">Semua kategori</option>
            @foreach ($categories as $category)
                <option value="{{ $category }}">{{ $category }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2">
        <button type="button" id="btn-reset" class="btn btn-outline-secondary w-100">Reset</button>
    </div>
</form>

<div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 g-3">
    @foreach ($products as $product)
        <div class="col product-item" data-name="{{ strtolower($product['name']) }}" data-category="{{ $product['category'] }}">
            <div class="card h-100 shadow-sm">
                <div class="card-body d-flex flex-column">
                    <span class="badge bg-info text-dark align-self-start mb-2">{{ $product['category'] }}</span>
                    <h6 class="card-title">{{ $product['name'] }}</h6>
                    <p class="card-text small text-muted flex-grow-1">{{ $product['description'] }}</p>
                    <div class="fw-bold text-primary">Rp {{ number_format($product['price'], 0, ',', '.') }}</div>
                    <small class="text-muted">Stok: {{ $product['stock'] }}</small>
                </div>
            </div>
        </div>
    @endforeach
    <div class="col-12" id="empty-state" style="display:none;">
        <div class="alert alert-light text-center">Produk tidak ditemukan.</div>
    </div>
</div>

<nav class="mt-4">
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
    function applyFilter() {
        const q = $('#search-input').val().toLowerCase();
        const cat = $('#category-filter').val();
        let visible = 0;

        $('.product-item').each(function () {
            const match = $(this).data('name').includes(q) && (!cat || $(this).data('category') === cat);
            $(this).toggle(match);
            if (match) visible++;
        });

        $('#empty-state').toggle(visible === 0);
    }

    $('#search-input').on('input', applyFilter);
    $('#category-filter').on('change', applyFilter);
    $('#btn-reset').on('click', function () {
        $('#search-input').val('');
        $('#category-filter').val('');
        applyFilter();
    });
    $('#filter-form').on('submit', function (e) { e.preventDefault(); });
</script>
@endpush