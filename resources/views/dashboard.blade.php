@php $role = auth()->user()->role ?? request('role', 'admin'); @endphp
@extends('layouts.app')@section('title','Dashboard')@section('content')
{{-- Variabel: $totalProducts, $totalCategories, $totalStock, $totalOrders, $totalUsers (admin), $myOrders (user) --}}
<h1>Dashboard</h1><p>Halo, {{ auth()->user()->name ?? 'Pengguna' }} ({{ $role }})</p>
<div class="grid">
<div class="card">Total produk<h2>{{ $totalProducts ?? 0 }}</h2></div>
<div class="card">Total kategori<h2>{{ $totalCategories ?? 0 }}</h2></div>
@if($role != 'user')<div class="card">Total stok<h2>{{ $totalStock ?? 0 }}</h2></div><div class="card">Pesanan<h2>{{ $totalOrders ?? 0 }}</h2></div>@endif
@if($role == 'admin')<div class="card">Akun pengguna<h2>{{ $totalUsers ?? 0 }}</h2></div>@endif
@if($role == 'user')<div class="card">Pesanan saya<h2>{{ $myOrders ?? 0 }}</h2></div>@endif
</div>@endsection
