@extends('layouts.guest')@section('title','Daftar')@section('content')
<form method="POST" action="/register">@csrf
<label>Nama</label><input name="name" value="{{ old('name') }}" required>
<label>Email</label><input type="email" name="email" value="{{ old('email') }}" required>
@error('email')<p class="err">{{ $message }}</p>@enderror
<label>Password</label><input type="password" name="password" required>
<label>Konfirmasi password</label><input type="password" name="password_confirmation" required>
<button>Daftar</button></form>
<p>Sudah punya akun? <a href="/login">Masuk</a></p>
@endsection
