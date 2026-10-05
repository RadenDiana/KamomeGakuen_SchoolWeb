@extends('layouts.guest')

@section('title', 'Profil Sekolah | KAMOME GAKUEN')
@section('hideNavbar', true)

@section('content')

{{-- HEADER --}}
<header class="page-header">
    <div class="container d-flex align-items-center justify-content-between py-4">
        <a href="{{ route('home') }}" class="page-back">
            <i class="fa-solid fa-chevron-left me-2"></i>Kembali
        </a>
        <h1 class="page-title mb-0">Profil Sekolah</h1>
    </div>
</header>

<main class="container py-5" style="max-width: 1100px;">
    <div class="d-flex flex-column gap-5">

        {{-- DESKRIPSI --}}
        <div>
            <div class="text-center mb-4">
                <h2 class="home-title">Profil Sekolah</h2>
                <div class="title-underline mx-auto"></div>
            </div>
            <p class="mb-0" style="line-height: 1.9; white-space: pre-wrap;">
                {{ $profil?->deskripsi ?? 'Deskripsi sekolah belum diisi.' }}
            </p>
        </div>

        {{-- VISI MISI --}}
        <div class="row g-4">
            <div class="col-md-6">
                <div class="form-kartu p-4 h-100">
                    <h5 class="fw-bold text-center mb-3" style="color: var(--bs-primary);">VISI</h5>
                    <p class="mb-0 text-center" style="white-space: pre-wrap;">{{ $profil?->visi ?? 'Belum ada visi.' }}</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-kartu p-4 h-100">
                    <h5 class="fw-bold text-center mb-3" style="color: var(--bs-primary);">MISI</h5>
                    <p class="mb-0" style="white-space: pre-wrap;">{{ $profil?->misi ?? 'Belum ada misi.' }}</p>
                </div>
            </div>
        </div>

        {{-- STRUKTUR ORGANISASI --}}
        <div>
            <div class="text-center mb-4">
                <h2 class="home-title">Struktur Sekolah</h2>
                <div class="title-underline mx-auto"></div>
            </div>

            <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-4">
                @forelse ($strukturs as $item)
                    <div class="col">
                        <div class="produk-card h-100 text-center">
                            <img src="{{ $item->foto_url }}" alt="{{ $item->nama }}" class="produk-card-img">
                            <div class="pt-2 px-1">
                                <div class="fw-bold">{{ $item->nama }}</div>
                                <div class="small text-secondary">{{ $item->jabatan }}</div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12"><p class="text-center text-secondary mb-0">Belum ada data struktur.</p></div>
                @endforelse
            </div>
        </div>

    </div>
</main>

@endsection