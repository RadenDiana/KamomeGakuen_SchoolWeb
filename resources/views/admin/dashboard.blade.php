@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

<div class="admin-header-line pb-3 mb-4">
    <h1 class="admin-title">Dashboard</h1>
    <div class="admin-breadcrumb">Dashboard</div>
</div>

{{-- STATISTIK --}}
<div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 g-3 mb-4">
    <div class="col">
        <div class="card admin-outline admin-card shadow-sm h-100">
            <div class="card-body text-center py-2">
    <div class="fw-semibold" style="font-size: 12px;">Ekstrakurikuler</div>
    <div class="d-flex align-items-center justify-content-center gap-2">
        <i class="fa-solid fa-people-group"></i>
        <span class="fs-5 fw-semibold">{{ $totalEkstrakurikuler }}</span>
    </div>
</div>
        </div>
    </div>
    <div class="col">
        <div class="card admin-outline admin-card shadow-sm h-100">
            <div class="card-body text-center py-3">
                <div class="fw-semibold" style="font-size: 13px;">Data Siswa</div>
                <div class="d-flex align-items-center justify-content-center gap-2 mt-1">
                    <i class="fa-solid fa-user-graduate"></i>
                    <span class="fs-4 fw-semibold">{{ $totalSiswa }}</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card admin-outline admin-card shadow-sm h-100">
            <div class="card-body text-center py-3">
                <div class="fw-semibold" style="font-size: 13px;">Data Akun</div>
                <div class="d-flex align-items-center justify-content-center gap-2 mt-1">
                    <i class="fa-solid fa-user-gear"></i>
                    <span class="fs-4 fw-semibold">{{ $totalAkun }}</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card admin-outline admin-card shadow-sm h-100">
            <div class="card-body text-center py-3">
                <div class="fw-semibold" style="font-size: 13px;">Galeri</div>
                <div class="d-flex align-items-center justify-content-center gap-2 mt-1">
                    <i class="fa-solid fa-image"></i>
                    <span class="fs-4 fw-semibold">{{ $totalGaleri }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

@if ($pesanBelumDibalas > 0)
    <a href="{{ route('admin.kontak.index') }}"
        class="alert alert-warning py-2 d-flex align-items-center gap-2 text-decoration-none text-dark mb-3">
        <i class="fa-solid fa-envelope fs-4"></i>
        <div>
            <strong>{{ $pesanBelumDibalas }} pesan belum dibalas</strong>
            <span class="d-block small">Klik untuk membuka halaman Kontak.</span>
        </div>
    </a>
@endif

{{-- RATING + KOMENTAR (kiri) | PENGUMUMAN (kanan) --}}
<div class="row g-4">
    <div class="col-lg-6 col-dashboard-kiri">
        {{-- PENINGKATAN RATING --}}
        <div class="card admin-outline admin-card shadow-sm mb-4 flex-shrink-0">
            <div class="card-body p-4">
                <h2 class="h5 fw-semibold mb-3">Peningkatan Rating</h2>

                @if ($totalRating > 0)
                    <div class="d-flex align-items-center gap-1 mb-3">
                        <span class="fs-4 fw-bold lh-1">{{ $rataRating }}</span>
                        <i class="fa-solid fa-star text-warning rating-star-kecil"></i>
                        <span class="rating-teks-kecil text-secondary">rata-rata dari {{ $totalRating }} penilaian</span>
                    </div>

                    @for ($i = 5; $i >= 1; $i--)
                        <div class="rating-row">
                            <span class="rating-stars text-warning">
                                @for ($j = 0; $j < $i; $j++)
                                    <i class="fa-solid fa-star"></i>
                                @endfor
                            </span>
                            <div class="progress rating-bar">
                                <div class="progress-bar bg-warning"
                                     style="width: {{ $totalRating > 0 ? round(($ratingPerBintang[$i] ?? 0) / $totalRating * 100) : 0 }}%;"></div>
                            </div>
                            <span class="rating-count">{{ $ratingPerBintang[$i] ?? 0 }}</span>
                        </div>
                    @endfor
                @else
                    <p class="text-secondary small mb-0">Belum ada penilaian dari pengunjung.</p>
                @endif
            </div>
        </div>

        {{-- KOMENTAR TERBARU --}}
        <div class="card admin-outline admin-card shadow-sm card-komentar">
            <div class="card-body p-4">
                <h2 class="h5 fw-semibold mb-4">Komentar Terbaru</h2>

                <div class="admin-scroll pe-2">
                    <div class="d-flex flex-column gap-3">
                        @forelse ($komentarTerbaru as $item)
                            <button type="button"
                                    class="admin-item admin-item-btn p-3 position-relative"
                                    data-item="{{ json_encode($item) }}"
                                    onclick="showKomentar(this)">
                                @if ($item->created_at->isToday())
                                    <span class="dot-baru"></span>
                                @endif
                                <span class="small d-block text-truncate">&ldquo;{{ \Illuminate\Support\Str::limit($item->komentar, 60) }}&rdquo;</span>
                                <div class="mt-1">
                                    @for ($j = 0; $j < $item->rating; $j++)
                                        <i class="fa-solid fa-star text-warning" style="font-size: 11px;"></i>
                                    @endfor
                                    <span class="small text-secondary ms-1">{{ $item->created_at->translatedFormat('d M Y') }}</span>
                                </div>
                            </button>
                        @empty
                            <p class="text-secondary mb-0">Belum ada komentar.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6 col-dashboard-kiri">
        {{-- PENGUMUMAN TERBARU --}}
        <div class="card admin-outline admin-card shadow-sm card-komentar">
            <div class="card-body p-4">
                <h2 class="h5 fw-semibold mb-4">Pengumuman Terbaru</h2>

                <div class="admin-scroll pe-2">
                    <div class="d-flex flex-column gap-3">
                        @forelse ($pengumumanTerbaru as $item)
                            <div class="admin-item d-flex align-items-center gap-3 p-3">
                                <i class="fa-solid fa-file-lines fs-4" style="color: #2563eb;"></i>
                                <div>
                                    <div class="fw-semibold small">{{ $item->judul }}</div>
                                    <div class="small text-secondary">
                                        {{ $item->tanggal?->translatedFormat('d M Y') ?? $item->created_at->translatedFormat('d M Y') }}
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-secondary mb-0">Belum ada pengumuman.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection