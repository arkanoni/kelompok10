@extends('layouts.app')

@section('title', 'Data Produk')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
    <h4 class="mb-0">Data Produk</h4>
    <a href="{{ route('products.create') }}" class="btn btn-primary">+ Tambah Produk</a>
</div>

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if ($errors->has('stock'))
    <div class="alert alert-danger">{{ $errors->first('stock') }}</div>
@endif

{{-- Cari & filter --}}
<form method="GET" action="{{ route('products.index') }}" class="row g-2 mb-3">
    <div class="col-md-5">
        <input type="text" name="q" class="form-control" placeholder="Cari nama produk..."
               value="{{ request('q') }}">
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
    <div class="col-md-3 d-flex gap-2">
        <button class="btn btn-secondary flex-fill">Cari</button>
        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">Reset</a>
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
                            @if ($product->description)
                                <small class="text-muted">{{ \Illuminate\Support\Str::limit($product->description, 50) }}</small>
                            @endif
                        </td>
                        <td><span class="badge bg-info text-dark">{{ $product->category->nama_kategori }}</span></td>
                        <td class="text-end">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                        <td>
                            {{-- Update cepat stok --}}
                            <form action="{{ route('products.stock', $product) }}" method="POST" class="input-group input-group-sm">
                                @csrf
                                @method('PATCH')
                                <input type="number" min="0" name="stock" value="{{ $product->stock }}"
                                       class="form-control {{ $product->stock <= 5 ? 'border-danger' : '' }}">
                                <button class="btn btn-outline-primary" title="Simpan stok">Ubah</button>
                            </form>
                            @if ($product->stock <= 5)
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
    {{ $products->links('pagination::bootstrap-5') }}
</div>
@endsection

@push('scripts')
<script>
    // jQuery: konfirmasi hapus
    $('.form-delete').on('submit', function (e) {
        const name = $(this).data('name');
        if (!confirm('Yakin ingin menghapus produk "' + name + '"?')) {
            e.preventDefault();
        }
    });

    // jQuery: filter kategori langsung dikirim saat dipilih
    $('#category-filter').on('change', function () {
        $(this).closest('form').trigger('submit');
    });
</script>
@endpush
