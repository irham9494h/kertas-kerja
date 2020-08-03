<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TanggalSumberDana extends Model
{
    protected $table = 'sd_tanggal';

    protected $guarded = [];

    /**
     * Boot the Model.
     */
    public static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {
            foreach ($model->kertas_kerja as $pendapatan) {
                $pendapatan->delete();
            }

            foreach ($model->kertas_kerja_belanja as $belanja) {
                $belanja->delete();
            }

            foreach ($model->kertas_kerja_pembiayaan as $pembiayaan) {
                $pembiayaan->delete();
            }

            return true;
        });
    }

    public function tahun()
    {
        return $this->belongsTo(TahunSumberDana::class);
    }

    public function kertas_kerja()
    {
        return $this->hasMany(KertasKerjaPendapatan::class, 'sd_tanggal_id');
    }

    public function kertas_kerja_belanja()
    {
        return $this->hasMany(KertasKerjaBelanja::class, 'sd_tanggal_id');
    }

    public function kertas_kerja_pembiayaan()
    {
        return $this->hasMany(KertasKerjaPembiayaan::class, 'sd_tanggal_id');
    }

}
