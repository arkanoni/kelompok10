@extends('layouts.app')

@section('title', 'Data Produk')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
    <h4 class="mb-0">Data Produk</h4>
    <a href="{{ route('products.create') }}" class="btn btn-primary">+ Tambah Produk</a>
</div>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- Cari & filter (server-side) --}}
<form method="GET" action="{{ route('products.index') }}" class="row g-2 mb-3" id="filter-form">
    <div class="col-md-5">
        <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Cari nama produk...">
    </div>
    <div class="col-md-4">
        <select name="category" id="category-filter" class="form-select">
            <option value="">Semua kategori</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected(request('category') == $category->id)>{{ $category->nama_kategori }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3 d-flex gap-2">
        <button type="submit" class="btn btn-outline-primary flex-fill">Cari</button>
        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary flex-fill">Reset</a>
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
                @forelse ($products as $product)
                    <tr>
                        <td>{{ $products->firstItem() + $loop->index }}</td>
                        <td>
                            <div class="fw-semibold">{{ $product->name }}</div>
                            <small class="text-muted">{{ $product->description }}</small>
                        </td>
                        <td><span class="badge bg-info text-dark">{{ $product->category->nama_kategori }}</span></td>
                        <td class="text-end">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                        <td>
                            <form action="{{ route('products.stock', $product) }}" method="POST" class="input-group input-group-sm">
                                @csrf
                                @method('PATCH')
                                <input type="number" name="stock" min="0" value="{{ $product->stock }}"
                                       class="form-control {{ $product->isLowStock() ? 'border-danger' : '' }}">
                                <button class="btn btn-outline-primary" title="Simpan stok">Ubah</button>
                            </form>
                            @if ($product->isLowStock())
                                <small class="text-danger">Stok menipis</small>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('products.destroy', $product) }}" method="POST"
                                  class="d-inline form-delete" data-name="{{ $product->name }}">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">Belum ada data produk.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">
    {{ $products->links() }}
</div>
@endsection

@push('scripts')
<script>
    // Filter kategori langsung terkirim saat dipilih
    $('#category-filter').on('change', function () {
        $('#filter-form').submit();
    });

    // Konfirmasi sebelum hapus
    $('.form-delete').on('submit', function (e) {
        if (!confirm('Yakin ingin menghapus produk "' + $(this).data('name') + '"?')) {
            e.preventDefault();
        }
    });
</script>
@endpush
