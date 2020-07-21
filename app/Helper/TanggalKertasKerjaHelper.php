<?php

namespace App\Helper;

use App\Models\TanggalSumberDana;
use Carbon\Carbon;

class TanggalKertasKerjaHelper
{

    public static function checkTheFirstDate($request)
    {
        $jenisPembahasan = $request['jenis_pembahasan'] == 'murni' ? 'struktur_murni' : 'struktur_perubahan';
        $date = Carbon::createFromFormat('d/m/Y', $request['tanggal'])->format('Y-m-d');
        $getLowerTanggal = TanggalSumberDana::where('sd_tahun_id', '=', $request['sb_tahun_id'])
            ->whereDate('tanggal', '<', $date)->orderBy('tanggal', 'desc')
            ->where('jenis_pembahasan', '=', $jenisPembahasan)
            ->first();
        if (empty($getLowerTanggal))
            return true;

        return false;
    }

    public static function checkIfDateIsLowerThanOtherDate($request)
    {
        $jenisPembahasan = $request['jenis_pembahasan'] == 'murni' ? 'struktur_murni' : 'struktur_perubahan';
        $date = Carbon::createFromFormat('d/m/Y', $request['tanggal'])->format('Y-m-d');
        $getLowerTanggal = TanggalSumberDana::where('sd_tahun_id', '=', $request['sb_tahun_id'])
            ->whereDate('tanggal', '>', $date)->orWhereDate('tanggal', '=', $date)
            ->orderBy('tanggal', 'desc')
            ->where('jenis_pembahasan', '=', $jenisPembahasan)
            ->count();

        if ($getLowerTanggal > 0)
            return false;

        return true;
    }

}
