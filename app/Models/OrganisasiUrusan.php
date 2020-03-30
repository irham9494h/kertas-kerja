<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrganisasiUrusan extends Model
{
    protected $table = 'org_urusan';

    protected $guarded = [];

    public function bidang()
    {
        return $this->hasMany(OrganisasiBidang::class);
    }
}
