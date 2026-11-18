{{-- Partial form dipakai create & edit. $product (array) opsional. --}}
@php
    $p = $product ?? [];
    $categories = ['Makanan', 'Minuman', 'Kebutuhan Rumah'];
@endphp

<div class="mb-3">
    <label for="category" class="form-label">Kategori</label>
    <select name="category" id="category" class="form-select">
        <option value="">-- Pilih kategori --</option>
        @foreach ($categories as $category)
            <option value="{{ $category }}" @selected(($p['category'] ?? '') === $category)>{{ $category }}</option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label for="name" class="form-label">Nama Produk</label>
    <input type="text" name="name" id="name" class="form-control" value="{{ $p['name'] ?? '' }}">
</div>

<div class="mb-3">
    <label for="description" class="form-label">Deskripsi <span class="text-muted">(opsional)</span></label>
    <textarea name="description" id="description" rows="3" class="form-control">{{ $p['description'] ?? '' }}</textarea>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label for="price" class="form-label">Harga (Rp)</label>
        <input type="number" step="0.01" min="0" name="price" id="price" class="form-control" value="{{ $p['price'] ?? '' }}">
    </div>
    <div class="col-md-6 mb-3">
        <label for="stock" class="form-label">Stok</label>
        <input type="number" min="0" name="stock" id="stock" class="form-control" value="{{ $p['stock'] ?? 0 }}">
    </div>
</div>

<div class="d-flex gap-2">
    <button type="submit" class="btn btn-primary">Simpan</button>
    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">Batal</a>
</div>