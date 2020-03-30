<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrganisasiSubUnit extends Model
{
    protected $table = 'org_sub_unit';

    protected $guarded = [];

    public function unit()
    {
        return $this->belongsTo(OrganisasiUnit::class);
    }

}
