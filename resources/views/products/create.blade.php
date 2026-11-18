@extends('layouts.app')

@section('title', 'Tambah Produk')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card shadow-sm">
            <div class="card-header fw-semibold">Tambah Produk</div>
            <div class="card-body">
                <form action="#" method="POST" id="product-form">
                    @include('products._form')
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $('#product-form').on('submit', function (e) {
        e.preventDefault();
        alert('Produk disimpan (simulasi tampilan).');
        window.location.href = "{{ route('products.index') }}";
    });
</script>
@endpush