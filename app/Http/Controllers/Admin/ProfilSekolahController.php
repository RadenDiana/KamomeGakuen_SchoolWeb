<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProfilSekolah;
use App\Models\StrukturSekolah;
use Illuminate\Http\Request;

class ProfilSekolahController extends Controller
{
    public function index()
    {
        $profil    = ProfilSekolah::first() ?? ProfilSekolah::create([]);
        $strukturs = StrukturSekolah::orderBy('nama')->get();

        return view('admin.profil', compact('profil', 'strukturs'));
    }

    public function update(Request $request)
    {
        $profil = ProfilSekolah::first() ?? ProfilSekolah::create([]);

        $data = $request->validate([
            'deskripsi' => 'nullable|string|max:5000',
            'visi'      => 'nullable|string|max:3000',
            'misi'      => 'nullable|string|max:3000',
        ]);

        $profil->update($data);

        return back()->with('success', 'Profil sekolah berhasil diperbarui.');
    }

    /* ===== Struktur organisasi ===== */

    public function strukturStore(Request $request)
    {
        $data = $this->validasiStruktur($request);

        if ($request->hasFile('foto')) $data['foto'] = $this->simpanFoto($request);

        StrukturSekolah::create($data);

        return back()->with('success', 'Anggota struktur berhasil ditambahkan.');
    }

    public function strukturUpdate(Request $request, StrukturSekolah $struktur)
    {
        $data = $this->validasiStruktur($request);

        if ($request->hasFile('foto')) {
            $this->hapusFoto($struktur->foto);
            $data['foto'] = $this->simpanFoto($request);
        }

        $struktur->update($data);

        return back()->with('success', 'Anggota struktur berhasil diperbarui.');
    }

    public function strukturDestroy(StrukturSekolah $struktur)
    {
        $this->hapusFoto($struktur->foto);
        $struktur->delete();

        return back()->with('success', 'Anggota struktur berhasil dihapus.');
    }

    private function validasiStruktur(Request $request): array
    {
        return $request->validate([
            'nama'    => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'foto'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);
    }

    private function simpanFoto(Request $request): string
    {
        $file = $request->file('foto');
        $nama = time() . '_struktur_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
        $file->move(public_path('assets/images/profil'), $nama);

        return $nama;
    }

    private function hapusFoto(?string $nama): void
    {
        if ($nama && file_exists(public_path('assets/images/profil/' . $nama))) {
            unlink(public_path('assets/images/profil/' . $nama));
        }
    }
}