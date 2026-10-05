@extends('layouts.admin')

@section('title', 'Profil Sekolah')

@section('content')

<div class="admin-header-line pb-3 mb-4">
    <h1 class="admin-title">Profil Sekolah</h1>
    <div class="admin-breadcrumb">Dashboard / Profil Sekolah</div>
</div>

{{-- DESKRIPSI, VISI, MISI --}}
<form action="{{ route('admin.profil.update') }}" method="POST">
    @csrf
    @method('PUT')

    <div class="card admin-outline admin-card shadow-sm mb-4">
        <div class="card-body p-4">
            <h2 class="h6 fw-bold text-uppercase mb-3">Deskripsi Sekolah</h2>

            <textarea name="deskripsi" rows="4"
                      class="form-control bg-body-tertiary border-0 mb-3 py-2"
                      placeholder="Deskripsi sekolah...">{{ old('deskripsi', $profil->deskripsi) }}</textarea>

            <label class="form-label small fw-semibold">Visi</label>
            <textarea name="visi" rows="3"
                      class="form-control bg-body-tertiary border-0 mb-3 py-2">{{ old('visi', $profil->visi) }}</textarea>

            <label class="form-label small fw-semibold">Misi</label>
            <textarea name="misi" rows="4"
                      class="form-control bg-body-tertiary border-0 py-2">{{ old('misi', $profil->misi) }}</textarea>
        </div>
        <div class="admin-footer px-4 py-3 d-flex justify-content-center">
            <button type="submit" class="btn btn-primary px-5" style="min-width: 280px;">Simpan</button>
        </div>
    </div>
</form>

{{-- STRUKTUR ORGANISASI --}}
<div class="admin-header-line pb-3 mb-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
    <h1 class="admin-title">Struktur Sekolah</h1>
    <button class="btn btn-primary" onclick="prepareCreate()">
        <i class="fa-solid fa-plus me-1"></i> Struktur
    </button>
</div>

<div class="card admin-card border-0 shadow-sm overflow-hidden mb-4">
    <div class="card-body p-4 admin-scroll">
        <div class="row row-cols-2 row-cols-md-4 g-3">
            @forelse ($strukturs as $item)
                <div class="col">
                    <button type="button" class="btn p-0 w-100 border-0"
                            data-item="{{ json_encode($item) }}"
                            onclick="showDetail(this)">
                        <img src="{{ $item->foto_url }}"
                             class="rounded-3 shadow-sm w-100 object-fit-cover"
                             style="height: 115px;" alt="{{ $item->nama }}">
                        <div class="text-start mt-1">
                            <div class="small fw-semibold text-truncate">{{ $item->nama }}</div>
                            <div class="small text-secondary">{{ $item->jabatan }}</div>
                        </div>
                    </button>
                </div>
            @empty
                <div class="col"><p class="text-secondary mb-0">Belum ada anggota struktur.</p></div>
            @endforelse
        </div>
    </div>
</div>

{{-- DETAIL STRUKTUR --}}
<div class="card admin-outline admin-card shadow-sm mx-auto d-none" id="detailPanel" style="max-width: 920px;">
    <div class="card-body p-4 position-relative">
        <button type="button" class="btn-close position-absolute top-0 end-0 m-3" onclick="closeDetail()"></button>

        <div class="d-flex flex-column flex-md-row align-items-md-start gap-4">
            <div class="flex-shrink-0">
                <img id="detailImage" src="" class="rounded-3"
                     style="width: 140px; height: 170px; object-fit: cover;" alt="Foto">
            </div>
            <div class="flex-grow-1">
                <h2 class="h4 fw-bold" id="detailNama">-</h2>
                <p class="text-secondary mb-0" id="detailJabatan">-</p>
            </div>
        </div>
    </div>
    <div class="admin-footer d-flex flex-wrap justify-content-center gap-3 px-4 py-3">
        <form id="formDelete" action="" method="POST"
              onsubmit="return confirm('Apakah Anda yakin ingin menghapus anggota struktur ini?')">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger px-4">Hapus</button>
        </form>
        <button class="btn btn-primary px-4" onclick="prepareEditFromDetail()">Edit</button>
        <button class="btn btn-outline-secondary bg-white px-4" onclick="closeDetail()">Tutup</button>
    </div>
</div>

{{-- MODAL TAMBAH / EDIT STRUKTUR --}}
<div class="modal fade" id="modalForm" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form id="formMain" method="POST" enctype="multipart/form-data"
              class="modal-content border-0 rounded-4 overflow-hidden">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">

            <div class="modal-body p-4 p-md-5">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <h2 class="h5 fw-bold text-uppercase mb-0" id="modalTitle">Tambah Struktur</h2>
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
                        <input type="text" name="nama" id="inputNama" required
                               class="form-control bg-body-tertiary border-0 mb-3 py-2"
                               placeholder="Nama lengkap">

                        <input type="text" name="jabatan" id="inputJabatan" required
                               class="form-control bg-body-tertiary border-0 py-2"
                               placeholder="Jabatan (contoh: Kepala Sekolah)">
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
            ? '{{ asset("assets/images/profil") }}/' + item.foto
            : '{{ asset("assets/images/sekolah/logokamome.png") }}';
    }

    function showDetail(btn) {
        current = JSON.parse(btn.dataset.item);

        document.getElementById('detailImage').src = fotoPreview(current);
        document.getElementById('detailNama').innerText = current.nama;
        document.getElementById('detailJabatan').innerText = current.jabatan;
        document.getElementById('formDelete').action = `/admin/profil/struktur/${current.id}`;

        const panel = document.getElementById('detailPanel');
        panel.classList.remove('d-none');
        panel.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    function closeDetail() {
        document.getElementById('detailPanel').classList.add('d-none');
    }

    function prepareCreate() {
        document.getElementById('modalTitle').innerText = 'Tambah Struktur';
        form.action = "{{ route('admin.profil.struktur.store') }}";
        document.getElementById('formMethod').value = 'POST';
        form.reset();
        document.getElementById('modalPreview').src = '{{ asset("assets/images/sekolah/logokamome.png") }}';
        getModal().show();
    }

    function prepareEditFromDetail() {
        if (current) openEditModal();
    }

    function openEditModal() {
        document.getElementById('modalTitle').innerText = 'Data Struktur';
        form.action = `/admin/profil/struktur/${current.id}`;
        document.getElementById('formMethod').value = 'PUT';
        document.getElementById('inputNama').value    = current.nama;
        document.getElementById('inputJabatan').value = current.jabatan;
        document.getElementById('modalPreview').src   = fotoPreview(current);
        getModal().show();
    }
</script>

@endsection