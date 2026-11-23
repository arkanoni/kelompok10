@extends('layouts.app')@section('title','Edit kategori')@section('content')
{{-- Variabel: $category (data yang diedit) --}}
<h1>Edit kategori</h1><div class="card">@include('categories._form')</div>@endsection
