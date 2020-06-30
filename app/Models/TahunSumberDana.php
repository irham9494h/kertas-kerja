<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TahunSumberDana extends Model
{
    protected $table = 'sd_tahun';

    protected $guarded = [];

    public static function pembahasanStrukturMurni(){
        return 0;
    }

    public static function strukturMurniFix()
    {
        return 1;
    }

    public static function pembahasanStrukturPerubahan(){
        return 0;
    }

    public static function strukturPerubahanFix()
    {
        return 1;
    }

    public function tanggal()
    {
        return $this->hasMany(TanggalSumberDana::class, 'sd_tahun_id');
    }
}
