<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RekeningJenis extends Model
{
    protected $table = 'rek_jenis';

    protected $guarded = [];

    public function kelompok()
    {
        return $this->belongsTo(RekeningKelompok::class);
    }

    public function obyek()
    {
        return $this->hasMany(RekeningObyek::class);
    }

}
