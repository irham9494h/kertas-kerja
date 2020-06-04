<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrganisasiBidang extends Model
{
    protected $table = 'org_bidang';

    protected $guarded = [];

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {
            foreach ($model->unit as $unit) {
                $unit->delete();
            }
            return true;
        });
    }

    public function urusan()
    {
        return $this->belongsTo(OrganisasiUrusan::class);
    }

    public function unit(){
        return $this->hasMany(OrganisasiUnit::class, 'bidang_id');
    }
}
