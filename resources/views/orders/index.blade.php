@extends('layouts.app')@section('title','Pesanan')@section('content')
{{-- Variabel: $orders (id, user->name, total, status, created_at). Status: pending, diproses, dikirim, selesai, dibatalkan --}}
<h1>Data pesanan</h1><div class="card">
<form method="GET"><select name="status" onchange="this.form.submit()" style="width:auto"><option value="">Semua status</option>@foreach(['pending','diproses','dikirim','selesai','dibatalkan'] as $s)<option @selected(request('status') == $s)>{{ $s }}</option>@endforeach</select></form>
<table><tr><th>No</th><th>Pembeli</th><th>Tanggal</th><th>Total</th><th>Status</th><th>Aksi</th></tr>
@forelse(($orders ?? []) as $o)
<tr><td>#{{ $o->id }}</td><td>{{ $o->user->name ?? '-' }}</td><td>{{ $o->created_at }}</td><td>Rp {{ number_format($o->total, 0, ',', '.') }}</td><td><span class="tag">{{ $o->status }}</span></td><td><a class="btn" href="/orders/{{ $o->id }}">Detail</a></td></tr>
@empty<tr><td colspan="6">Belum ada pesanan.</td></tr>@endforelse
</table></div>@endsection
