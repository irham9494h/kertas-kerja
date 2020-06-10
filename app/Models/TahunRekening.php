<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TahunRekening extends Model
{
    protected $table = 'tahun_rekening';

    protected $guarded = [];

    public function rekening_akun()
    {
        return $this->hasMany(RekeningAkun::class);
    }
}
