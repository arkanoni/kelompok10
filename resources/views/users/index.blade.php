@extends('layouts.app')@section('title','Akun pengguna')@section('content')
{{-- Variabel: $users (id, name, email, role) --}}
<h1>Akun pengguna</h1><div class="card"><a class="btn" href="/users/create">Tambah akun</a><br><br>
<table><tr><th>Nama</th><th>Email</th><th>Role</th><th>Aksi</th></tr>
@forelse(($users ?? []) as $u)
<tr><td>{{ $u->name }}</td><td>{{ $u->email }}</td><td><span class="tag">{{ $u->role }}</span></td>
<td><a class="btn" href="/users/{{ $u->id }}/edit">Edit</a> <x-delete :action="'/users/'.$u->id" /></td></tr>
@empty<tr><td colspan="4">Belum ada akun.</td></tr>@endforelse
</table></div>@endsection
