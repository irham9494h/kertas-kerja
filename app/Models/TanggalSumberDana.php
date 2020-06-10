<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TanggalSumberDana extends Model
{
    protected $table = 'sd_tanggal';

    protected $guarded = [];

    public function tahun(){
        return $this->belongsTo(TahunSumberDana::class);
    }

    public function kertas_kerja()
    {
        return $this->hasMany(KertasKerja::class, 'sd_tanggal_id');
    }

}