@extends('layouts.app')@section('title','Status pesanan')@section('content')
{{-- Variabel: $order (id, status, total, created_at, items: product->name, qty, price) --}}
<h1>Pesanan #{{ $order->id ?? '-' }}</h1><div class="card"><p>Status: <span class="tag">{{ $order->status ?? '-' }}</span><br>Tanggal: {{ $order->created_at ?? '-' }}<br>Total: Rp {{ number_format($order->total ?? 0, 0, ',', '.') }}</p>
<table><tr><th>Produk</th><th>Jumlah</th><th>Harga</th></tr>
@forelse(($order->items ?? []) as $i)<tr><td>{{ $i->product->name ?? '-' }}</td><td>{{ $i->qty }}</td><td>Rp {{ number_format($i->price, 0, ',', '.') }}</td></tr>
@empty<tr><td colspan="3">Tidak ada item.</td></tr>@endforelse</table></div>
<a class="btn gray" href="/shop/orders">Kembali</a>@endsection
