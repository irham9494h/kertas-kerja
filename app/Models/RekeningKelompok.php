<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RekeningKelompok extends Model
{
    protected $table = 'rek_kelompok';

    protected $guarded = [];

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {
            foreach ($model->jenis as $jenis) {
                $jenis->delete();
            }
            return true;
        });
    }

    public function akun()
    {
        return $this->belongsTo(RekeningAkun::class);
    }

    public function jenis()
    {
        return $this->hasMany(RekeningJenis::class, 'kelompok_id');
    }
}
