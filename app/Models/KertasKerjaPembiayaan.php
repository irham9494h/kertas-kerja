<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KertasKerjaPembiayaan extends Model
{
    protected $table = 'kertas_kerja_pembiayaan';

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

}
