<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    public function index(Request $request)
    {
        $produks = Produk::query()
            ->when($request->filled('search'), function ($q) use ($request) {
                $cari = $request->search;
                $q->where(fn ($w) => $w
                    ->where('judul', 'like', "%{$cari}%")
                    ->orWhere('deskripsi', 'like', "%{$cari}%"));
            })
            ->when($request->sort === 'a-z',       fn ($q) => $q->orderBy('judul'))
            ->when($request->sort === 'z-a',       fn ($q) => $q->orderByDesc('judul'))
            ->when($request->sort === 'termurah',  fn ($q) => $q->orderBy('harga'))
            ->when($request->sort === 'termahal',  fn ($q) => $q->orderByDesc('harga'))
            ->when(! in_array($request->sort, ['a-z', 'z-a', 'termurah', 'termahal']), fn ($q) => $q->latest())
            ->paginate(8)
            ->withQueryString();

        return view('admin.produk', compact('produks'));
    }

    public function store(Request $request)
    {
        $data = $this->validasi($request);

        if ($request->hasFile('foto')) $data['foto'] = $this->simpanFoto($request);

        Produk::create($data);

        return back()->with('success', 'Produk berhasil ditambahkan.');
    }

    public function update(Request $request, Produk $produk)
    {
        $data = $this->validasi($request);

        if ($request->hasFile('foto')) {
            $this->hapusFoto($produk->foto);
            $data['foto'] = $this->simpanFoto($request);
        }

        $produk->update($data);

        return back()->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Produk $produk)
    {
        $this->hapusFoto($produk->foto);
        $produk->delete();

        return back()->with('success', 'Produk berhasil dihapus.');
    }

    private function validasi(Request $request): array
    {
        return $request->validate([
            'judul'     => 'required|string|max:255',
            'deskripsi' => 'required|string|max:2000',
            'harga'     => 'required|integer|min:0',
            'foto'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);
    }

    private function simpanFoto(Request $request): string
    {
        $file = $request->file('foto');
        $nama = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
        $file->move(public_path('assets/images/produk'), $nama);

        return $nama;
    }

    private function hapusFoto(?string $nama): void
    {
        if ($nama && file_exists(public_path('assets/images/produk/' . $nama))) {
            unlink(public_path('assets/images/produk/' . $nama));
        }
    }
}