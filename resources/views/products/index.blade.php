@extends('layouts.app')@section('title','Produk')@section('content')
{{-- Variabel: $products (id, name, price, stock, category->name), $categories (id, name) untuk filter --}}
<h1>Data produk</h1><div class="card"><a class="btn" href="/products/create">Tambah produk</a>
<form method="GET" style="display:inline-block;margin-left:12px"><select name="category" onchange="this.form.submit()" style="width:auto;margin:0"><option value="">Semua kategori</option>@foreach(($categories ?? []) as $c)<option value="{{ $c->id }}" @selected(request('category') == $c->id)>{{ $c->name }}</option>@endforeach</select></form><br><br>
<table><tr><th>Produk</th><th>Kategori</th><th>Harga</th><th>Stok</th><th>Aksi</th></tr>
@forelse(($products ?? []) as $p)
<tr><td>{{ $p->name }}</td><td>{{ $p->category->name ?? '-' }}</td><td>Rp {{ number_format($p->price, 0, ',', '.') }}</td><td>{{ $p->stock }}</td>
<td><a class="btn" href="/products/{{ $p->id }}/edit">Edit</a> <x-delete :action="'/products/'.$p->id" /></td></tr>
@empty<tr><td colspan="5">Belum ada produk.</td></tr>@endforelse
</table></div>@endsection
