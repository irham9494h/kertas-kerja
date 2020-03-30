<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrganisasiUnit extends Model
{
    protected $table = 'org_unit';

    protected $guarded = [];

    public function bidang()
    {
        return $this->belongsTo(OrganisasiBidang::class);
    }

    public function sub_unit()
    {
        return $this->hasMany(OrganisasiSubUnit::class);
    }
}
