<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrganisasiBidang extends Model
{
    protected $table = 'org_bidang';

    protected $guarded = [];

    public function urusan()
    {
        return $this->belongsTo(OrganisasiUrusan::class);
    }

    public function unit(){
        return $this->hasMany(OrganisasiUnit::class);
    }
}
