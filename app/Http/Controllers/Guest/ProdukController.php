<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Produk;

class ProdukController extends Controller
{
    public function index()
    {
        return view('guest.produk', [
            'produks' => Produk::orderBy('judul')->paginate(12),
        ]);
    }
}