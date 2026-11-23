@extends('layouts.app')@section('title','Pesanan saya')@section('content')
{{-- Variabel: $orders milik pembeli yang login (id, total, status, created_at) --}}
<h1>Riwayat pesanan</h1><div class="card"><table><tr><th>No</th><th>Tanggal</th><th>Total</th><th>Status</th><th></th></tr>
@forelse(($orders ?? []) as $o)
<tr><td>#{{ $o->id }}</td><td>{{ $o->created_at }}</td><td>Rp {{ number_format($o->total, 0, ',', '.') }}</td><td><span class="tag">{{ $o->status }}</span></td><td><a class="btn" href="/shop/orders/{{ $o->id }}">Lihat status</a></td></tr>
@empty<tr><td colspan="5">Kamu belum punya pesanan. <a href="/shop">Lihat produk</a></td></tr>@endforelse
</table></div>@endsection
