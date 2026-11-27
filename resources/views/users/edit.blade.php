@extends('layouts.app')@section('title','Edit akun')@section('content')
{{-- Variabel: $user (data yang diedit) --}}
<h1>Edit akun</h1><div class="card">@include('users._form')</div>@endsection
