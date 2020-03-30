<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RekeningAkun extends Model
{
    protected $table = 'rek_akun';

    protected $guarded = [];

    public function kelompok()
    {
        return $this->hasMany(RekeningKelompok::class);
    }

}
