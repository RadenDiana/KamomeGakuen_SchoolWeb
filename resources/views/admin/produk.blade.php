@extends('layouts.admin')

@section('title', 'Produk')

@section('content')

{{-- HEADER --}}
<div class="admin-header-line pb-3 mb-4 d-flex justify-content-between align-items-start flex-wrap gap-3">
    <div>
        <h1 class="admin-title">Produk</h1>
        <div class="admin-breadcrumb">Dashboard / Produk</div>
    </div>
    <button class="btn btn-primary" onclick="prepareCreate()">
        <i class="fa-solid fa-plus me-1"></i> Produk
    </button>
</div>

{{-- SEARCH & SORT --}}
<form action="{{ route('admin.produk.index') }}" method="GET" class="row g-3 mb-4">
    <div class="col-md-7">
        <div class="input-group admin-search">
            <span class="input-group-text"><i class="fa-solid fa-magnifying-glass"></i></span>
            <input type="text" name="search" value="{{ request('search') }}"
                   class="form-control" placeholder="Cari produk..."
                   onchange="this.form.submit()">
        </div>
    </div>
    <div class="col-md-5">
        <select name="sort" class="form-select admin-filter-select" onchange="this.form.submit()">
            <option value="">Urutan berdasarkan</option>
            <option value="terbaru" {{ request('sort') == 'terbaru' ? 'selected' : '' }}>Terbaru</option>
            <option value="a-z" {{ request('sort') == 'a-z' ? 'selected' : '' }}>A - Z</option>
            <option value="z-a" {{ request('sort') == 'z-a' ? 'selected' : '' }}>Z - A</option>
            <option value="termurah" {{ request('sort') == 'termurah' ? 'selected' : '' }}>Harga Termurah</option>
            <option value="termahal" {{ request('sort') == 'termahal' ? 'selected' : '' }}>Harga Termahal</option>
        </select>
    </div>
</form>

{{-- GRID + PAGINATION --}}
<div class="card admin-card border-0 shadow-sm overflow-hidden mb-4">
    <div class="card-body p-4 admin-scroll">
        <div class="row row-cols-2 row-cols-md-4 g-3">
            @forelse ($produks as $item)
                <div class="col">
                    <button type="button"
                            class="btn p-0 w-100 border-0"
                            data-item="{{ json_encode($item) }}"
                            onclick="showDetail(this)">
                        <img src="{{ $item->foto_url }}"
                             class="rounded-3 shadow-sm w-100 object-fit-cover {{ $item->foto ? '' : 'obj-contain bg-white' }}"
                             style="height: 115px;" alt="{{ $item->judul }}">
                        <div class="text-start mt-1">
                            <div class="small fw-semibold text-truncate">{{ $item->judul }}</div>
                            <div class="small text-primary fw-semibold">{{ $item->harga_format }}</div>
                        </div>
                    </button>
                </div>
            @empty
                <div class="col"><p class="text-secondary mb-0">Tidak ada data produk.</p></div>
            @endforelse
        </div>
    </div>
    <div class="admin-footer d-flex flex-wrap justify-content-between align-items-center gap-2 px-4 py-3">
        <span class="small text-secondary">
            Menampilkan {{ $produks->firstItem() ?? 0 }} sampai {{ $produks->lastItem() ?? 0 }} dari {{ $produks->total() }} data
        </span>
        {{ $produks->links() }}
    </div>
</div>

{{-- DETAIL --}}
<div class="card admin-outline admin-card shadow-sm mx-auto d-none" id="detailPanel" style="max-width: 920px;">
    <div class="card-body p-4 position-relative">
        <button type="button" class="btn-close position-absolute top-0 end-0 m-3" onclick="closeDetail()"></button>

        <div class="d-flex flex-column flex-md-row align-items-md-start gap-4">
            <div class="flex-shrink-0">
                <img id="detailImage" src="" class="rounded-3"
                     style="width: 140px; height: 140px; object-fit: cover;" alt="Produk">
            </div>
            <div class="flex-grow-1">
                <h2 class="h4 fw-bold" id="detailTitle">-</h2>
                <div class="h6 text-primary fw-semibold mb-2" id="detailHarga">-</div>
                <p class="small mb-0 text-secondary" id="detailDeskripsi" style="white-space: pre-wrap;">-</p>
            </div>
        </div>
    </div>
    <div class="admin-footer d-flex flex-wrap justify-content-center gap-3 px-4 py-3">
        <form id="formDelete" action="" method="POST"
              onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?')">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger px-4">Hapus</button>
        </form>
        <button class="btn btn-primary px-4" onclick="prepareEditFromDetail()">Edit</button>
        <button class="btn btn-outline-secondary bg-white px-4" onclick="closeDetail()">Tutup</button>
    </div>
</div>

{{-- MODAL TAMBAH / EDIT --}}
<div class="modal fade" id="modalForm" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form id="formMain" method="POST" enctype="multipart/form-data"
              class="modal-content border-0 rounded-4 overflow-hidden">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">

            <div class="modal-body p-4 p-md-5">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <h2 class="h5 fw-bold text-uppercase mb-0" id="modalTitle">Tambah Produk</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="row g-4 align-items-start">
                    <div class="col-md-5 text-center">
                        <img id="modalPreview" src="{{ asset('assets/images/sekolah/logokamome.png') }}"
                             class="rounded-3 border shadow-sm mb-3"
                             style="width: 150px; height: 150px; object-fit: cover;" alt="Foto">
                        <input type="file" name="foto" id="inputFoto"
                               class="form-control form-control-sm" accept="image/*"
                               onchange="previewGambar(this)">
                    </div>

                    <div class="col-md-7">
                        <input type="text" name="judul" id="inputJudul" required
                               class="form-control bg-body-tertiary border-0 mb-3 py-2"
                               placeholder="Judul Produk">

                        <input type="number" name="harga" id="inputHarga" min="0" required
                               class="form-control bg-body-tertiary border-0 mb-3 py-2"
                               placeholder="Harga (contoh: 25000)">

                        <textarea name="deskripsi" id="inputDeskripsi" rows="4" required
                                  class="form-control bg-body-tertiary border-0 mb-3 py-2"
                                  placeholder="Deskripsi produk"></textarea>
                    </div>
                </div>
            </div>

            <div class="admin-footer px-4 py-3 d-flex justify-content-center">
                <button type="submit" class="btn btn-primary px-5" style="min-width: 280px;">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
    const form = document.getElementById('formMain');
    let current = null;

    function getModal() {
        return bootstrap.Modal.getOrCreateInstance(document.getElementById('modalForm'));
    }

    function previewGambar(input) {
        if (input.files && input.files[0]) {
            document.getElementById('modalPreview').src = URL.createObjectURL(input.files[0]);
        }
    }

    function fotoPreview(item) {
        return item.foto
            ? '{{ asset("assets/images/produk") }}/' + item.foto
            : '{{ asset("assets/images/sekolah/logokamome.png") }}';
    }

    function showDetail(btn) {
        current = JSON.parse(btn.dataset.item);

        document.getElementById('detailImage').src = fotoPreview(current);
        document.getElementById('detailTitle').innerText = current.judul;
        document.getElementById('detailHarga').innerText =
            'Rp ' + Number(current.harga).toLocaleString('id-ID');
        document.getElementById('detailDeskripsi').innerText = current.deskripsi;

        document.getElementById('formDelete').action = `/admin/produk/${current.id}`;

        const panel = document.getElementById('detailPanel');
        panel.classList.remove('d-none');
        panel.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    function closeDetail() {
        document.getElementById('detailPanel').classList.add('d-none');
    }

    function prepareCreate() {
        document.getElementById('modalTitle').innerText = 'Tambah Produk';
        form.action = "{{ route('admin.produk.store') }}";
        document.getElementById('formMethod').value = 'POST';
        form.reset();
        document.getElementById('modalPreview').src = '{{ asset("assets/images/sekolah/logokamome.png") }}';
        getModal().show();
    }

    function prepareEditFromDetail() {
        if (current) openEditModal();
    }

    function openEditModal() {
        document.getElementById('modalTitle').innerText = 'Data Produk';
        form.action = `/admin/produk/${current.id}`;
        document.getElementById('formMethod').value = 'PUT';
        document.getElementById('inputJudul').value     = current.judul;
        document.getElementById('inputHarga').value     = current.harga;
        document.getElementById('inputDeskripsi').value = current.deskripsi;
        document.getElementById('modalPreview').src     = fotoPreview(current);
        getModal().show();
    }
</script>

@endsection