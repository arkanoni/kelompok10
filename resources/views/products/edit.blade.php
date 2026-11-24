@extends('layouts.app')

@section('title', 'Edit Produk')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card shadow-sm">
            <div class="card-header fw-semibold">Edit Produk</div>
            <div class="card-body">
                <form action="{{ route('products.update', $product) }}" method="POST" id="product-form" novalidate>
                    @csrf
                    @method('PUT')
                    @include('products._form')
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $('#product-form').on('submit', function () {
        $(this).find('button[type=submit]').prop('disabled', true).text('Menyimpan...');
    });
</script>
@endpush
