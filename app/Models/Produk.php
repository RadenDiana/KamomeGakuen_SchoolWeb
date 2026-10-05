<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $fillable = ['judul', 'deskripsi', 'harga', 'foto'];

    public function getFotoUrlAttribute(): string
    {
        return $this->foto
            ? asset('assets/images/produk/' . $this->foto)
            : asset('assets/images/sekolah/logokamome.png');
    }

    public function getHargaFormatAttribute(): string
    {
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }
}