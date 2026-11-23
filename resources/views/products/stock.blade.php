@extends('layouts.app')@section('title','Stok')@section('content')
{{-- Variabel: $products (id, name, stock, category->name). Update lewat PATCH /products/{id}/stock --}}
<h1>Pantau stok</h1><div class="card"><table><tr><th>Produk</th><th>Kategori</th><th>Stok</th><th>Ubah stok</th></tr>
@forelse(($products ?? []) as $p)
<tr><td>{{ $p->name }}</td><td>{{ $p->category->name ?? '-' }}</td><td>{{ $p->stock }} @if($p->stock <= 5)<span class="tag">menipis</span>@endif</td>
<td><form method="POST" action="/products/{{ $p->id }}/stock" style="display:flex;gap:8px">@csrf @method('PATCH')<input type="number" name="stock" min="0" value="{{ $p->stock }}" style="width:90px;margin:0"><button class="btn">Simpan</button></form></td></tr>
@empty<tr><td colspan="4">Belum ada produk.</td></tr>@endforelse
</table></div>@endsection
