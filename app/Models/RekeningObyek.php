<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RekeningObyek extends Model
{
    protected $table = 'rek_obyek';

    protected $guarded = [];

    public function jenis()
    {
        return $this->belongsTo(RekeningJenis::class);
    }

    public function rincian_obyek()
    {
        return $this->hasMany(RekeningRincianObyek::class, 'obyek_id', 'id');
    }
}
