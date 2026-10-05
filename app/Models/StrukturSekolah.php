<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StrukturSekolah extends Model
{
    protected $table = 'struktur_sekolah';
    protected $fillable = ['nama', 'jabatan', 'foto'];

    public function getFotoUrlAttribute(): string
    {
        return $this->foto
            ? asset('assets/images/profil/' . $this->foto)
            : asset('assets/images/sekolah/logokamome.png');
    }
}