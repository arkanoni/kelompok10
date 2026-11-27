@extends('layouts.guest')@section('title','Login')@section('content')
<form method="POST" action="/login">@csrf
<label>Email</label><input type="email" name="email" value="{{ old('email') }}" required>
@error('email')<p class="err">{{ $message }}</p>@enderror
<label>Password</label><input type="password" name="password" required>
<label><input type="checkbox" name="remember" style="width:auto"> Ingat saya</label><br><br>
<button>Masuk</button></form>
<p>Belum punya akun? <a href="/register">Daftar sebagai pembeli</a></p>
@endsection
