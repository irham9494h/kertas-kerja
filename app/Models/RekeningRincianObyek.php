<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RekeningRincianObyek extends Model
{
    protected $table = 'rek_rincian_obyek';

    protected $guarded = [];

    public function obyek()
    {
        return $this->belongsTo(RekeningObyek::class, 'obyek_id');
    }

    public function kertas_kerja_pendapatan()
    {
        return $this->hasMany(KertasKerjaPendapatan::class, 'rincian_obyek_id', 'id');
    }

    public function kertas_kerja_belanja()
    {
        return $this->hasMany(KertasKerjaBelanja::class, 'rincian_obyek_id', 'id');
    }

    public function kertas_kerja_pembiayaan()
    {
        return $this->hasMany(KertasKerjaPembiayaan::class, 'rincian_obyek_id', 'id');
    }
}
