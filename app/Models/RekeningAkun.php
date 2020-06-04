<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RekeningAkun extends Model
{
    protected $table = 'rek_akun';

    protected $guarded = [];

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {
            foreach ($model->kelompok as $kelompok) {
                $kelompok->delete();
            }
            return true;
        });
    }

    public function kelompok()
    {
        return $this->hasMany(RekeningKelompok::class, 'akun_id');
    }

}
