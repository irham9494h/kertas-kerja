<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TahunSumberDana extends Model
{
    protected $table = 'sd_tahun';

    protected $guarded = [];

    /**
     * Boot the Model.
     */
    public static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {
            foreach ($model->tanggal as $tanggal) {

                foreach ($tanggal->kertas_kerja as $pendapatan) {
                    $pendapatan->delete();
                }

                foreach ($tanggal->kertas_kerja_belanja as $belanja) {
                    $belanja->delete();
                }

                foreach ($tanggal->kertas_kerja_pembiayaan as $pembiayaan) {
                    $pembiayaan->delete();
                }

                $tanggal->delete();
            }

            return true;
        });
    }

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
