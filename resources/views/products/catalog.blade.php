@extends('layouts.app')

@section('title', 'Katalog Produk')

@section('content')
<h4 class="mb-3">Katalog Produk</h4>

<form method="GET" action="{{ route('products.catalog') }}" class="row g-2 mb-4">
    <div class="col-md-6">
        <input type="text" name="q" class="form-control" placeholder="Cari produk..." value="{{ request('q') }}">
    </div>
    <div class="col-md-4">
        <select name="category" id="category-filter" class="form-select">
            <option value="">Semua kategori</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected(request('category') == $category->id)>
                    {{ $category->nama_kategori }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2">
        <button class="btn btn-secondary w-100">Cari</button>
    </div>
</form>

<div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 g-3">
    @forelse ($products as $product)
        <div class="col">
            <div class="card h-100 shadow-sm">
                <div class="card-body d-flex flex-column">
                    <span class="badge bg-info text-dark align-self-start mb-2">{{ $product->category->nama_kategori }}</span>
                    <h6 class="card-title">{{ $product->name }}</h6>
                    <p class="card-text small text-muted flex-grow-1">
                        {{ \Illuminate\Support\Str::limit($product->description, 70) }}
                    </p>
                    <div class="fw-bold text-primary">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                    <small class="text-muted">Stok: {{ $product->stock }}</small>
                    {{-- Tombol beli: disambungkan ke modul pesanan nanti --}}
                </div>
            </div>
        </div>
    @empty
        <div class="col-12"><div class="alert alert-light text-center">Produk tidak ditemukan.</div></div>
    @endforelse
</div>

<div class="mt-4">{{ $products->links('pagination::bootstrap-5') }}</div>
@endsection

@push('scripts')
<script>
    $('#category-filter').on('change', function () {
        $(this).closest('form').trigger('submit');
    });
</script>
@endpush
