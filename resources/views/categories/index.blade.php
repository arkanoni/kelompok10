@php $role = auth()->user()->role ?? request('role', 'admin'); @endphp
@extends('layouts.app')@section('title','Kategori')@section('content')
{{-- Variabel: $categories (id, name, description, products_count). CRUD hanya admin, pegawai hanya melihat --}}
<h1>Kategori barang</h1><div class="card">
@if($role == 'admin')<a class="btn" href="/categories/create">Tambah kategori</a><br><br>@endif
<table><tr><th>Nama</th><th>Deskripsi</th><th>Jumlah produk</th>@if($role == 'admin')<th>Aksi</th>@endif</tr>
@forelse(($categories ?? []) as $c)
<tr><td>{{ $c->name }}</td><td>{{ $c->description }}</td><td>{{ $c->products_count }}</td>
@if($role == 'admin')<td><a class="btn" href="/categories/{{ $c->id }}/edit">Edit</a> <x-delete :action="'/categories/'.$c->id" /></td>@endif</tr>
@empty<tr><td colspan="4">Belum ada kategori.</td></tr>@endforelse
</table></div>@endsection
