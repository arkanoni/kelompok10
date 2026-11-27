@extends('layouts.app')

@section('title', 'Katalog Produk')

@section('content')
<h4 class="mb-3">Katalog Produk</h4>

<form method="GET" action="{{ route('products.catalog') }}" class="row g-2 mb-4" id="filter-form">
    <div class="col-md-6">
        <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Cari produk...">
    </div>
    <div class="col-md-4">
        <select name="category" id="category-filter" class="form-select">
            <option value="">Semua kategori</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected(request('category') == $category->id)>{{ $category->nama_kategori }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2">
        <a href="{{ route('products.catalog') }}" class="btn btn-outline-secondary w-100">Reset</a>
    </div>
</form>

<div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 g-3">
    @forelse ($products as $product)
        <div class="col">
            <div class="card h-100 shadow-sm">
                <div class="card-body d-flex flex-column">
                    <span class="badge bg-info text-dark align-self-start mb-2">{{ $product->category->nama_kategori }}</span>
                    <h6 class="card-title">{{ $product->name }}</h6>
                    <p class="card-text small text-muted flex-grow-1">{{ $product->description }}</p>
                    <div class="fw-bold text-primary">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                    <small class="{{ $product->stock > 0 ? 'text-muted' : 'text-danger' }}">
                        {{ $product->stock > 0 ? 'Stok: ' . $product->stock : 'Stok habis' }}
                    </small>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-light text-center">Produk tidak ditemukan.</div>
        </div>
    @endforelse
</div>

<div class="mt-4">
    {{ $products->links() }}
</div>
@endsection

@push('scripts')
<script>
    $('#category-filter').on('change', function () {
        $('#filter-form').submit();
    });
    // Pencarian: kirim otomatis setelah berhenti mengetik 500 ms
    let timer;
    $('input[name=q]').on('input', function () {
        clearTimeout(timer);
        timer = setTimeout(() => $('#filter-form').submit(), 500);
    });
</script>
@endpush
