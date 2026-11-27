@extends('layouts.app')@section('title','Produk')@section('content')
{{-- Variabel: $products (id, name, price, stock, category->name), $categories (id, name) --}}
<h1>Daftar produk</h1>
<form method="GET" class="card"><select name="category" onchange="this.form.submit()" style="width:auto;margin:0"><option value="">Semua kategori</option>@foreach(($categories ?? []) as $c)<option value="{{ $c->id }}" @selected(request('category') == $c->id)>{{ $c->name }}</option>@endforeach</select></form>
<div class="grid">@forelse(($products ?? []) as $p)
<div class="card"><span class="tag">{{ $p->category->name ?? '-' }}</span><h3>{{ $p->name }}</h3><p>Rp {{ number_format($p->price, 0, ',', '.') }}<br>Stok: {{ $p->stock }}</p>
@if($p->stock > 0)<a class="btn" href="/shop/checkout/{{ $p->id }}">Beli</a>@else<span class="tag">Stok habis</span>@endif</div>
@empty<p>Belum ada produk.</p>@endforelse</div>@endsection
