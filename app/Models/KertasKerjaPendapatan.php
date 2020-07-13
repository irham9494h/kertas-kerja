<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KertasKerjaPendapatan extends Model
{
    protected $table = 'kertas_kerja_pendapatan';

    protected $guarded = [];

    public function tanggal(){
        return $this->belongsTo(TanggalSumberDana::class, 'sd_tanggal_id');
    }

    public function unit()
    {
        return $this->belongsTo(OrganisasiUnit::class, 'unit_id');
    }

    public function jenis()
    {
        return $this->belongsTo(RekeningJenis::class, 'jenis_id');
    }

    public function rincian_obyek()
    {
        return $this->belongsTo(RekeningRincianObyek::class, 'rincian_obyek_id');
    }

    public function kertas_kerja_belanja()
    {
        return $this->hasMany(KertasKerjaBelanja::class, 'pendapatan_id');
    }
}
