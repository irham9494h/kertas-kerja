<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RekeningKelompok extends Model
{
    protected $table = 'rek_kelompok';

    protected $guarded = [];

    public function akun()
    {
        return $this->belongsTo(RekeningAkun::class);
    }

    public function jenis()
    {
        return $this->hasMany(RekeningJenis::class);
    }
}
