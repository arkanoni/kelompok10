@extends('layouts.guest')
@section('title', 'Login')
@section('content')
<form method="POST" action="{{ route('login.store') }}">
    @csrf
    <label for="email">Email</label>
    <input id="email" type="email" name="email" value="{{ is_string(old('email')) ? old('email') : '' }}" autocomplete="username" required autofocus>
    <label for="password">Password</label>
    <input id="password" type="password" name="password" autocomplete="current-password" required>
    <label for="remember"><input id="remember" type="checkbox" name="remember" value="1" style="width:auto" @checked(old('remember', false))> Ingat saya</label><br><br>
    <button type="submit">Masuk</button>
</form>
<p>Belum punya akun? <a href="{{ route('register') }}">Daftar sebagai pembeli</a></p>
@endsection
