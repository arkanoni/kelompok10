{{-- Variabel: $categories (id, name), $product (hanya saat edit) --}}
@php $p = $product ?? null; @endphp
<form method="POST" action="{{ $p ? '/products/'.$p->id : '/products' }}">@csrf @if($p) @method('PUT') @endif
<label>Nama produk</label><input name="name" value="{{ $p->name ?? '' }}" required>
<label>Kategori</label><select name="category_id" required><option value="">Pilih kategori</option>@foreach(($categories ?? []) as $c)<option value="{{ $c->id }}" @selected(($p->category_id ?? '') == $c->id)>{{ $c->name }}</option>@endforeach</select>
<label>Harga (Rp)</label><input type="number" name="price" min="0" value="{{ $p->price ?? '' }}" required>
<label>Stok</label><input type="number" name="stock" min="0" value="{{ $p->stock ?? 0 }}" required>
<button class="btn">Simpan produk</button> <a class="btn gray" href="/products">Batal</a></form>
