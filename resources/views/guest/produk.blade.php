@extends('layouts.guest')

@section('title', 'Produk | KAMOME GAKUEN')
@section('hideNavbar', true)

@section('content')

{{-- HEADER --}}
<header class="page-header">
    <div class="container d-flex align-items-center justify-content-between py-4">
        <a href="{{ route('home') }}#produk" class="page-back">
            <i class="fa-solid fa-chevron-left me-2"></i>Kembali
        </a>
        <h1 class="page-title mb-0">Produk</h1>
    </div>
</header>

<main class="container py-5" style="max-width: 1100px;">
    <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-3">
        @forelse ($produks as $item)
            <div class="col">
                <div class="produk-card h-100">
                    <img src="{{ $item->foto_url }}" alt="{{ $item->judul }}" class="produk-card-img">
                    <div class="pt-2 px-1">
                        <div class="produk-card-title mb-1">{{ $item->judul }}</div>
                        <div class="fw-bold produk-harga mb-1">{{ $item->harga_format }}</div>
                        <p class="produk-card-text mb-0">{{ $item->deskripsi }}</p>
                    </div>
                </div>
            </div>
        @empty
            <div class="col"><p class="text-center text-secondary mb-0">Belum ada produk.</p></div>
        @endforelse
    </div>

    <div class="mt-5 d-flex justify-content-center">
        {{ $produks->links() }}
    </div>
</main>

@endsection