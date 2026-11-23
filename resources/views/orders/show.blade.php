@php $role = auth()->user()->role ?? request('role', 'admin'); @endphp
@extends('layouts.app')@section('title','Detail pesanan')@section('content')
{{-- Variabel: $order (id, user->name, status, total, created_at, items: product->name, qty, price). Hapus hanya admin --}}
<h1>Pesanan #{{ $order->id ?? '-' }}</h1>
<div class="card"><p>Pembeli: {{ $order->user->name ?? '-' }}<br>Tanggal: {{ $order->created_at ?? '-' }}<br>Total: Rp {{ number_format($order->total ?? 0, 0, ',', '.') }}</p>
<table><tr><th>Produk</th><th>Jumlah</th><th>Harga</th></tr>
@forelse(($order->items ?? []) as $i)<tr><td>{{ $i->product->name ?? '-' }}</td><td>{{ $i->qty }}</td><td>Rp {{ number_format($i->price, 0, ',', '.') }}</td></tr>
@empty<tr><td colspan="3">Tidak ada item.</td></tr>@endforelse</table></div>
<div class="card"><form method="POST" action="/orders/{{ $order->id ?? 0 }}/status" style="display:flex;gap:8px">@csrf @method('PATCH')
<select name="status" style="width:auto;margin:0">@foreach(['pending','diproses','dikirim','selesai','dibatalkan'] as $s)<option @selected(($order->status ?? '') == $s)>{{ $s }}</option>@endforeach</select>
<button class="btn">Ubah status</button></form></div>
@if($role == 'admin')<x-delete :action="'/orders/'.($order->id ?? 0)" />@endif
<a class="btn gray" href="/orders">Kembali</a>@endsection
