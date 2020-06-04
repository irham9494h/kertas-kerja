<?php

namespace App\Helper;

use App\Models\TanggalSumberDana;
use Carbon\Carbon;

class TanggalKertasKerjaHelper
{

    public static function checkTheFirstDate($request)
    {
        $date = Carbon::createFromFormat('d/m/Y', $request['tanggal'])->format('Y-m-d');
        $getLowerTanggal = TanggalSumberDana::where('sd_tahun_id', '=', $request['sb_tahun_id'])
            ->whereDate('tanggal', '<', $date)->orderBy('tanggal', 'desc')->first();
        if (empty($getLowerTanggal))
            return true;

        return false;
    }

    public static function checkIfDateIsLowerThanOtherDate($request)
    {
        $date = Carbon::createFromFormat('d/m/Y', $request['tanggal'])->format('Y-m-d');
        $getLowerTanggal = TanggalSumberDana::where('sd_tahun_id', '=', $request['sb_tahun_id'])
            ->whereDate('tanggal', '>', $date)->orWhereDate('tanggal', '=', $date)
            ->orderBy('tanggal', 'desc')->count();
        if ($getLowerTanggal > 0)
            return false;

        return true;
    }

}
