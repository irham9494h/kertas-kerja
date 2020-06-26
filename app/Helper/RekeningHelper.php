<?php

namespace App\Helper;

use App\Models\TahunRekening;

class RekeningHelper
{

    public static function tahunRekening()
    {
        $tahun = TahunRekening::aktif()->firstOrFail();

        return $tahun;
    }

}




