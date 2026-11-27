@extends('layouts.app')@section('title','Edit produk')@section('content')
{{-- Variabel: $product (data yang diedit), $categories --}}
<h1>Edit produk</h1><div class="card">@include('products._form')</div>@endsection
