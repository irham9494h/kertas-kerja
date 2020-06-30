<?php

namespace App\Http\Controllers\Laporan;

use App\Helper\DataLaporan2020;
use App\Http\Controllers\Controller;
use App\Models\KertasKerjaPendapatan;
use App\Models\RekeningAkun;
use App\Models\TahunSumberDana;
use App\Models\TanggalSumberDana;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;

class LaporanKertasKerjaController extends Controller
{

    public function index()
    {
        $tahuns = TahunSumberDana::orderBy('tahun', 'desc')->get();
        return view('laporan.kertas-kerja.kertas-kerja', compact('tahuns'));
    }

    public function getTanggalByTahun($tahun_id){
        $tanggal = TanggalSumberDana::where('sd_tahun_id', '=', $tahun_id)
            ->orderBy('tanggal', 'desc')
            ->get();
        return $tanggal;
    }

    public function whereLatestTanggalId()
    {
        $tahunKertasKerjaId = 20;
        $latestTanggalId = TanggalSumberDana::where('sd_tahun_id', '=', $tahunKertasKerjaId)->latest()->first();
        $query = function ($q) use ($latestTanggalId) {
            $q->where('sd_tanggal_id', '=', $latestTanggalId->id);
        };
        return $query;
    }

    public function laporan2020()
    {
        $sumberDanaFix = DataLaporan2020::data();

//        dd($sumberDanaFix);
        return view('laporan.kertas-kerja.kertas-kerja-2020', compact('sumberDanaFix'));
//        $pdf = PDF::loadview('laporan.kertas-kerja.kertas-kerja-2020', compact('sumberDanaFix'));
//        return $pdf->setPaper('Legal', 'portrait')->stream('Laporan', '.pdf');

    }

//    public function laporan2020()
//    {
//        $whereLatestTanggalId = $this->whereLatestTanggalId();
//
//        $arrayPendapatan = [];
//        $totalPendapatan = 0;
//        $noPendapatan = 1;
//        $totalLevel2 = 0;
//        $totalLevel3 = 0;
//        $noLevel2 = 1;
//        $noLevel3 = 0;
//
//        $pendapatan = RekeningAkun::with(['kelompok', 'kelompok.jenis', 'kelompok.jenis.kertas_kerja' => $whereLatestTanggalId])
//            ->whereHas('kelompok.jenis.kertas_kerja', $whereLatestTanggalId)
//            ->distinct()
//            ->get();
//
////        dd($pendapatan);
//
//        foreach ($pendapatan as $p) {
//
//            $arrayPendapatan[] = ['urutan' => $noPendapatan, 'key' => $p->nama_akun, 'nilai' => $totalPendapatan, 'level' => 1];
//
//            foreach ($p->kelompok as $k) {
//                $arrayPendapatan[] = ['urutan' => $noPendapatan . '.' . $noLevel2, 'key' => $k->nama_kelompok, 'nilai' => $totalLevel2, 'level' => 2];
//                $noLevel2++;
//
//                foreach ($k->jenis as $j) {
//                    if ($j->kertas_kerja->count() > 0) {
//                        $arrayPendapatan[] = ['urutan' => $noPendapatan . '.' . $noLevel3, 'key' => $j->nama_jenis, 'nilai' => $j->kertas_kerja->sum('nilai'), 'level' => 3];
//                        $totalPendapatan += $j->kertas_kerja->sum('nilai');
//                    }
//
//                    $noLevel3++;
//
//                }
//
//            }
//
//        }
//
//        dd($arrayPendapatan);
//
//        return view('laporan.kertas-kerja.kertas-kerja-2020');
//
////        $pdf = PDF::loadview('laporan.kertas-kerja.kertas-kerja-2020');
////        return $pdf->setPaper('Legal', 'portrait')->stream('Laporan', '.pdf');
//    }

}
