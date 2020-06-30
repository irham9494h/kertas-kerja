<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use const http\Client\Curl\Features\KERBEROS4;

class KertasKerjaBelanja extends Model
{
    protected $table = 'kertas_kerja_belanja';

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

    public function kertas_kerja_pendapatan()
    {
        return $this->belongsTo(KertasKerjaPendapatan::class, 'pendapatan_id');
    }
}
