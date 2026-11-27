<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Kategori - Sistem Toko</title>
    <!-- Menggunakan Bootstrap 5 untuk tampilan rapi -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Daftar Kategori Barang</h4>
                        <a href="{{ route('categories.create') }}" class="btn btn-light btn-sm fw-bold">+ Tambah Kategori</a>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <table class="table table-bordered table-striped align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Nama Kategori</th>
                                    <th>Slug</th>
                                    <th width="20%" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($categories as $index => $category)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $category->nama_kategori }}</td>
                                        <td><code>{{ $category->slug }}</code></td>
                                        <td class="text-center">
                                            <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                            <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">Belum ada data kategori.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
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
