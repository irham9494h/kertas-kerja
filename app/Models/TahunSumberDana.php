<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TahunSumberDana extends Model
{
    protected $table = 'sd_tahun';

    protected $guarded = [];

    public static function dalamPembahasan(){
        return 0;
    }

    public static function fix()
    {
        return 1;
    }

    public function tanggal()
    {
        return $this->hasMany(TanggalSumberDana::class, 'sd_tahun_id');
    }
}
