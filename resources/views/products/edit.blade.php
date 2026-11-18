@extends('layouts.app')

@section('title', 'Edit Produk')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card shadow-sm">
            <div class="card-header fw-semibold">Edit Produk</div>
            <div class="card-body">
                <form action="#" method="POST" id="product-form">
                    @include('products._form', [
                        'product' => [
                            'category'    => 'Makanan',
                            'name'        => 'Indomie Goreng',
                            'description' => 'Mi instan goreng 85 gr',
                            'price'       => 3500,
                            'stock'       => 120,
                        ],
                    ])
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
        alert('Perubahan disimpan (simulasi tampilan).');
        window.location.href = "{{ route('products.index') }}";
    });
</script>
@endpush