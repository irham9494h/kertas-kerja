<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RekeningJenis extends Model
{
    protected $table = 'rek_jenis';

    protected $guarded = [];

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {
            foreach ($model->obyek as $obyek) {
                $obyek->delete();
            }
            return true;
        });
    }

    public function kelompok()
    {
        return $this->belongsTo(RekeningKelompok::class);
    }

    public function obyek()
    {
        return $this->hasMany(RekeningObyek::class, 'jenis_id');
    }

    public function kertas_kerja()
    {
        return $this->hasMany(KertasKerja::class, 'jenis_id', 'id');
    }

    public function kertas_kerja_belanja()
    {
        return $this->hasMany(KertasKerjaBelanja::class, 'jenis_id', 'id');
    }

    public function kertas_kerja_pembiayaan()
    {
        return $this->hasMany(KertasKerjaPembiayaan::class, 'jenis_id', 'id');
    }
}
