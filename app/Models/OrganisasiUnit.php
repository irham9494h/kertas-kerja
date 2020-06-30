<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrganisasiUnit extends Model
{
    protected $table = 'org_unit';

    protected $guarded = [];

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {
            foreach ($model->sub_unit as $sub_unit) {
                $sub_unit->delete();
            }
            return true;
        });
    }

    public function bidang()
    {
        return $this->belongsTo(OrganisasiBidang::class);
    }

    public function sub_unit()
    {
        return $this->hasMany(OrganisasiSubUnit::class, 'unit_id');
    }

    public function kertas_kerja()
    {
        return $this->hasMany(KertasKerjaPendapatan::class, 'unit_id', 'id');
    }

    public function kertas_kerja_belanja()
    {
        return $this->hasMany(KertasKerjaBelanja::class, 'unit_id', 'id');
    }

    public function kertas_kerja_pembiayaan()
    {
        return $this->hasMany(KertasKerjaPembiayaan::class, 'unit_id', 'id');
    }

}
