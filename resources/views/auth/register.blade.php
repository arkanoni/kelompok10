@extends('layouts.guest')
@section('title', 'Daftar')
@section('content')
<p>Daftar sebagai pembeli.</p>
<form method="POST" action="{{ route('register.store') }}">
    @csrf
    <label for="name">Nama</label>
    <input id="name" name="name" value="{{ is_string(old('name')) ? old('name') : '' }}" maxlength="255" autocomplete="name" required autofocus>
    <label for="email">Email</label>
    <input id="email" type="email" name="email" value="{{ is_string(old('email')) ? old('email') : '' }}" maxlength="255" autocomplete="username" required>
    <label for="password">Password (minimal 8 karakter)</label>
    <input id="password" type="password" name="password" minlength="8" autocomplete="new-password" required>
    <label for="password_confirmation">Konfirmasi password</label>
    <input id="password_confirmation" type="password" name="password_confirmation" minlength="8" autocomplete="new-password" required>
    <button type="submit">Daftar</button>
</form>
<p>Sudah punya akun? <a href="{{ route('login') }}">Masuk</a></p>
@endsection
