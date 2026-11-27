@extends('layouts.app')@section('title','Profil')@section('content')
{{-- Data: auth()->user() --}}
<h1>Profil akun</h1><div class="card"><form method="POST" action="/profile">@csrf @method('PUT')
<label>Nama</label><input name="name" value="{{ auth()->user()->name ?? '' }}" required>
<label>Email</label><input type="email" name="email" value="{{ auth()->user()->email ?? '' }}" required>
<label>Password baru (kosongkan bila tidak diganti)</label><input type="password" name="password">
<label>Konfirmasi password baru</label><input type="password" name="password_confirmation">
<button class="btn">Simpan perubahan</button></form></div>@endsection
