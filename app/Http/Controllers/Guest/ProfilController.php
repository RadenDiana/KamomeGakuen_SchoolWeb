<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\ProfilSekolah;
use App\Models\StrukturSekolah;

class ProfilController extends Controller
{
    public function index()
    {
        return view('guest.profil', [
            'profil'    => ProfilSekolah::first(),
            'strukturs' => StrukturSekolah::orderBy('nama')->get(),
        ]);
    }
}