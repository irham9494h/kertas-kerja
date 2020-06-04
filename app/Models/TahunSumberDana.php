<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TahunSumberDana extends Model
{
    protected $table = 'sd_tahun';

    protected $guarded = [];

    public function tanggal(){
        return $this->hasMany(TanggalSumberDana::class, 'sd_tahun_id');
    }
}
