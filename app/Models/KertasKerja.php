<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KertasKerja extends Model
{
    protected $table = 'kertas_kerja';

    protected $guarded = [];

    public function tanggal(){
        return $this->belongsTo(TanggalSumberDana::class);
    }

    public function unit()
    {
        return $this->belongsTo(OrganisasiUnit::class, 'unit_id');
    }

    public function jenis()
    {
        return $this->belongsTo(RekeningJenis::class, 'jenis_id');
    }

    public function kertas_kerja_belanja()
    {
        return $this->hasMany(KertasKerjaBelanja::class, 'pendapatan_id');
    }
}
