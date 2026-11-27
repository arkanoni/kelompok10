@extends('layouts.app')

@section('title', 'Tambah Produk')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card shadow-sm">
            <div class="card-header fw-semibold">Tambah Produk</div>
            <div class="card-body">
                <form action="{{ route('products.store') }}" method="POST" id="product-form" novalidate>
                    @csrf
                    @include('products._form')
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Cegah klik ganda saat submit
    $('#product-form').on('submit', function () {
        $(this).find('button[type=submit]').prop('disabled', true).text('Menyimpan...');
    });
</script>
@endpush
