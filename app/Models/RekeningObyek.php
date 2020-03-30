<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RekeningObyek extends Model
{
    protected $table = 'rek_obyek';

    protected $guarded = [];

    public function kelompok()
    {
        return $this->belongsTo(RekeningKelompok::class);
    }
}
