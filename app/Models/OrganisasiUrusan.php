<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrganisasiUrusan extends Model
{
    protected $table = 'org_urusan';

    protected $guarded = [];

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {
            foreach ($model->bidang as $bidang) {
                $bidang->delete();
            }
            return true;
        });
    }

    public function bidang()
    {
        return $this->hasMany(OrganisasiBidang::class, 'urusan_id');
    }
}
