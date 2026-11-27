@extends('layouts.app')@section('title','Beli produk')@section('content')
{{-- Variabel: $product (id, name, price, stock) --}}
<h1>Beli produk</h1><div class="card"><h3>{{ $product->name ?? '-' }}</h3><p>Harga: Rp {{ number_format($product->price ?? 0, 0, ',', '.') }} | Stok: {{ $product->stock ?? 0 }}</p>
<form method="POST" action="/shop/orders">@csrf<input type="hidden" name="product_id" value="{{ $product->id ?? '' }}">
<label>Jumlah</label><input type="number" name="qty" min="1" max="{{ $product->stock ?? 1 }}" value="1" required>
<label>Catatan (opsional)</label><textarea name="note" rows="2"></textarea>
<button class="btn">Buat pesanan</button> <a class="btn gray" href="/shop">Batal</a></form></div>@endsection
