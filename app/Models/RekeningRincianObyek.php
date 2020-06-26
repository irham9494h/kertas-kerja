<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RekeningRincianObyek extends Model
{
    protected $table = 'rek_rincian_obyek';

    protected $guarded = [];

    public function obyek()
    {
        return $this->belongsTo(RekeningObyek::class);
    }
}
