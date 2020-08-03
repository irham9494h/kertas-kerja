<?php

namespace App\Http\Controllers\Laporan;

use App\Helper\DataLaporan2020;
use App\Http\Controllers\Controller;
use App\Http\Resources\LaporanKertasKerjaMurniResource;
use App\Models\KertasKerjaPendapatan;
use App\Models\RekeningAkun;
use App\Models\RekeningJenis;
use App\Models\TahunSumberDana;
use App\Models\TanggalSumberDana;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\DB;

class LaporanKertasKerjaController extends Controller
{

    public function index()
    {
        $tahuns = TahunSumberDana::orderBy('tahun', 'desc')->get();
        return view('laporan.kertas-kerja.kertas-kerja', compact('tahuns'));
    }

    public function whereLatestTanggalId($tahunId)
    {
        $tahunKertasKerjaId = $tahunId;
        $latestTanggalId = TanggalSumberDana::where('sd_tahun_id', '=', $tahunKertasKerjaId)->latest()->first();
        $query = function ($q) use ($latestTanggalId) {
            $q->where('sd_tanggal_id', '=', $latestTanggalId->id);
        };
        return $query;
    }

    public function laporanKertasKerja(Request $request)
    {
        $tahuns = TahunSumberDana::orderBy('tahun', 'desc')->get();

        $kertasKerjaPendapatan = DB::table('rek_jenis')
            ->join('rek_kelompok', 'rek_kelompok.id', '=', 'rek_jenis.kelompok_id')
            ->join('rek_akun', 'rek_akun.id', '=', 'rek_kelompok.akun_id')
            ->join('rek_obyek', 'rek_jenis.id', '=', 'rek_obyek.jenis_id')
            ->join('rek_rincian_obyek', 'rek_obyek.id', '=', 'rek_rincian_obyek.obyek_id')
            ->join('kertas_kerja_pendapatan', 'rek_rincian_obyek.id', '=', 'kertas_kerja_pendapatan.rincian_obyek_id')
            ->where('kertas_kerja_pendapatan.sd_tanggal_id', '=', $request->tanggal_id)
            ->select('rek_jenis.id as jenis_id', 'rek_jenis.kode as kode_jenis', 'rek_jenis.nama_jenis',
                'rek_akun.id as akun_id', 'rek_akun.kode as kode_akun', 'rek_akun.nama_akun',
                'rek_kelompok.id as kelompok_id', 'rek_kelompok.kode as kode_kelompok', 'rek_kelompok.nama_kelompok',
                'kertas_kerja_pendapatan.nilai')
            ->get();

        if (count($kertasKerjaPendapatan) > 0) {
            $pendapatan = $this->getFormat($kertasKerjaPendapatan);
        } else {
            $pendapatan = [];
        }

        $kertasKerjaBelanja = DB::table('rek_jenis')
            ->join('rek_kelompok', 'rek_kelompok.id', '=', 'rek_jenis.kelompok_id')
            ->join('rek_akun', 'rek_akun.id', '=', 'rek_kelompok.akun_id')
            ->join('rek_obyek', 'rek_jenis.id', '=', 'rek_obyek.jenis_id')
            ->join('rek_rincian_obyek', 'rek_obyek.id', '=', 'rek_rincian_obyek.obyek_id')
            ->join('kertas_kerja_belanja', 'rek_rincian_obyek.id', '=', 'kertas_kerja_belanja.rincian_obyek_id')
            ->where('kertas_kerja_belanja.sd_tanggal_id', '=', $request->tanggal_id)
            ->select('rek_jenis.id as jenis_id', 'rek_jenis.kode as kode_jenis', 'rek_jenis.nama_jenis',
                'rek_akun.id as akun_id', 'rek_akun.kode as kode_akun', 'rek_akun.nama_akun',
                'rek_kelompok.id as kelompok_id', 'rek_kelompok.kode as kode_kelompok', 'rek_kelompok.nama_kelompok',
                'kertas_kerja_belanja.nilai')
            ->get();

        if (count($kertasKerjaBelanja) > 0) {
            $belanja = $this->getFormat($kertasKerjaBelanja);
        } else {
            $belanja = [];
        }

        $kertasKerjaPembiayaan = DB::table('rek_jenis')
            ->join('rek_kelompok', 'rek_kelompok.id', '=', 'rek_jenis.kelompok_id')
            ->join('rek_akun', 'rek_akun.id', '=', 'rek_kelompok.akun_id')
            ->join('rek_obyek', 'rek_jenis.id', '=', 'rek_obyek.jenis_id')
            ->join('rek_rincian_obyek', 'rek_obyek.id', '=', 'rek_rincian_obyek.obyek_id')
            ->join('kertas_kerja_pembiayaan', 'rek_rincian_obyek.id', '=', 'kertas_kerja_pembiayaan.rincian_obyek_id')
            ->where('kertas_kerja_pembiayaan.sd_tanggal_id', '=', $request->tanggal_id)
            ->select('rek_jenis.id as jenis_id', 'rek_jenis.kode as kode_jenis', 'rek_jenis.nama_jenis',
                'rek_akun.id as akun_id', 'rek_akun.kode as kode_akun', 'rek_akun.nama_akun',
                'rek_kelompok.id as kelompok_id', 'rek_kelompok.kode as kode_kelompok', 'rek_kelompok.nama_kelompok',
                'kertas_kerja_pembiayaan.nilai')
            ->get();

        if (count($kertasKerjaPembiayaan) > 0) {
            $pembiayaan = $this->getFormat($kertasKerjaPembiayaan);
        } else {
            $pembiayaan = [];
        }

        return view('laporan.kertas-kerja.kertas-kerja', compact('pendapatan', 'belanja', 'pembiayaan', 'tahuns'));

    }

    public function getFormat($kertasKerjaPendapatan)
    {
        $akun = [
            'akun_id' => $kertasKerjaPendapatan->first()->akun_id,
            'kode_akun' => $kertasKerjaPendapatan->first()->kode_akun,
            'nama_akun' => $kertasKerjaPendapatan->first()->nama_akun
        ];

        $totalNilaiAkun = 0;
        $kelompok = [];

        foreach ($kertasKerjaPendapatan as $kertas) {
            array_push($kelompok, [
                'kelompok_id' => $kertas->kelompok_id,
                'kode_kelompok' => $kertas->kode_kelompok,
                'nama_kelompok' => $kertas->nama_kelompok
            ]);
        }

        $kelompokTemp = array_unique($kelompok, SORT_REGULAR);
        asort($kelompokTemp);

        $kelompok = [];

        foreach ($kelompokTemp as $kel) {
            $jenis = [];
            $totalNIlaiKelompok = 0;
            foreach ($kertasKerjaPendapatan as $rek_jenis) {
                if ($kel['nama_kelompok'] == $rek_jenis->nama_kelompok) {
                    array_push($jenis, [
                        'jenis_id' => $rek_jenis->jenis_id,
                        'kode_jenis' => $rek_jenis->kode_jenis,
                        'jenis' => $rek_jenis->nama_jenis,
                        'nilai' => $rek_jenis->nilai
                    ]);
                    $totalNIlaiKelompok += $rek_jenis->nilai;
                }
            }

            asort($jenis);
            $jenisTemp = [];

            foreach ($jenis as $value) {

                $key = array_search($value['jenis_id'], array_column($jenisTemp, 'jenis_id'));

                if (count($jenisTemp) > 0) {
                    if ($jenisTemp[$key]['jenis_id'] == $value['jenis_id']) {
                        $jenisTemp[$key]['nilai'] = $jenisTemp[$key]['nilai'] + $value['nilai'];
                    } else {
                        array_push($jenisTemp, $value);
                    }
                } else {
                    array_push($jenisTemp, $value);
                }
                $totalNilaiAkun += $value['nilai'];
            }

            array_push($kelompok, [
                'kelompok_id' => $kel['kelompok_id'],
                'kode_kelompok' => $kel['kode_kelompok'],
                'nama_kelompok' => $kel['nama_kelompok'],
                'nilai' => $totalNIlaiKelompok,
                'jenis' => $jenisTemp
            ]);

        }
        $akun['nilai'] = $totalNilaiAkun;
        $akun['kelompok'] = $kelompok;

        $akun = (object)$akun;
        return $akun;
    }

    public function getTanggalByTahun($tahun_id)
    {
        $tanggal = TanggalSumberDana::where('sd_tahun_id', '=', $tahun_id)
            ->orderBy('tanggal', 'desc')
            ->get();
        return $tanggal;
    }

    public
    function laporan2020()
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
