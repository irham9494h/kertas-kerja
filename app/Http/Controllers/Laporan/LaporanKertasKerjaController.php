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
    var $tanggalTerakhir = '';

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
        $perubahan = [];
        $perubahanBelanja = [];

        if ($request->pergeseran == 'on') {
            return $this->kertasKerjaPergeseran($request);
        } else {
            return $this->kertasKerjaMurni($request);
        }
    }

    public function kertasKerjaMurni($request)
    {
        $tahuns = TahunSumberDana::orderBy('tahun', 'desc')->get();
        $tanggals = TanggalSumberDana::where('sd_tahun_id', '=', $request->tahun_id)
            ->orderBy('tanggal', 'desc')->select('tanggal')->distinct()->get();

        $pendapatan = $this->kertasKerjaPendapatan($request);
        $belanja = $this->kertasKerjaBelanja($request);
        $pembiayaan = $this->kertasKerjaPembiayaan($request);

        if ($request->report == 0) {
            return view('laporan.kertas-kerja.kertas-kerja', compact('pendapatan', 'belanja',
                'pembiayaan', 'tahuns', 'tanggals', 'request'));
        } else {
            $tanggal = TanggalSumberDana::findOrFail($request->tanggal_id)->tanggal;
            $pdf = PDF::loadview('laporan.kertas-kerja.murni', compact('pendapatan', 'belanja', 'pembiayaan', 'tahuns', 'tanggal'));
            return $pdf->setPaper('A4')->stream('Laporan Kertas Kerja Murni (' . $request->tanggal . ')', '.pdf');
        }
    }

    public function kertasKerjaPergeseran($request)
    {

        $tahuns = TahunSumberDana::orderBy('tahun', 'desc')->get();
        $tanggals = TanggalSumberDana::where('sd_tahun_id', '=', $request->tahun_id)
            ->orderBy('tanggal', 'desc')->select('tanggal')->distinct()->get();
        $tanggal = $request->tanggal;
        $pergeseran = $request->pergeseran;

        $perubahanPendapatan = $this->kertasKerjaPendapatanPergeseran($tanggal);
        $perubahanBelanja = $this->kertasKerjaBelanjaPergeseran($tanggal);

        if ($request->report == 0) {
            return view('laporan.kertas-kerja.kertas-kerja-perubahan', compact(
                'perubahanPendapatan', 'perubahanBelanja',
                'tanggals',
                'tahuns',
                'pergeseran',
                'request'
            ));
        } else {
//                $tanggal = TanggalSumberDana::findOrFail($request->tanggal_id)->tanggal;
//                $pdf = PDF::loadview('laporan.kertas-kerja.murni', compact('pendapatan', 'belanja', 'pembiayaan', 'tahuns', 'tanggal'));
//                return $pdf->setPaper('A4')->stream('Laporan Kertas Kerja Murni (' . $request->tanggal . ')', '.pdf');
        }
    }

    protected function kertasKerjaPendapatan($request)
    {
        $kertasKerjaPendapatan = DB::table('rek_jenis')
            ->join('rek_kelompok', 'rek_kelompok.id', '=', 'rek_jenis.kelompok_id')
            ->join('rek_akun', 'rek_akun.id', '=', 'rek_kelompok.akun_id')
            ->join('rek_obyek', 'rek_jenis.id', '=', 'rek_obyek.jenis_id')
            ->join('rek_rincian_obyek', 'rek_obyek.id', '=', 'rek_rincian_obyek.obyek_id')
            ->join('kertas_kerja_pendapatan', 'rek_rincian_obyek.id', '=', 'kertas_kerja_pendapatan.rincian_obyek_id')
            ->join('sd_tanggal', 'kertas_kerja_pendapatan.sd_tanggal_id', '=', 'sd_tanggal.id')
            ->where('sd_tanggal.jenis_pembahasan', '=', 'struktur_murni')
            ->where('sd_tanggal.tanggal', '=', $request->tanggal)
            ->select('rek_jenis.id as jenis_id', 'rek_jenis.kode as kode_jenis', 'rek_jenis.nama_jenis',
                'rek_akun.id as akun_id', 'rek_akun.kode as kode_akun', 'rek_akun.nama_akun',
                'rek_kelompok.id as kelompok_id', 'rek_kelompok.kode as kode_kelompok', 'rek_kelompok.nama_kelompok',
                'kertas_kerja_pendapatan.nilai')
            ->get();

        if (count($kertasKerjaPendapatan) > 0) {
            return $pendapatan = $this->getFormat($kertasKerjaPendapatan);
        } else {
            return $pendapatan = [];
        }
    }

    protected function kertasKerjaBelanja($request)
    {
        $kertasKerjaBelanja = DB::table('rek_jenis')
            ->join('rek_kelompok', 'rek_kelompok.id', '=', 'rek_jenis.kelompok_id')
            ->join('rek_akun', 'rek_akun.id', '=', 'rek_kelompok.akun_id')
            ->join('rek_obyek', 'rek_jenis.id', '=', 'rek_obyek.jenis_id')
            ->join('rek_rincian_obyek', 'rek_obyek.id', '=', 'rek_rincian_obyek.obyek_id')
            ->join('kertas_kerja_belanja', 'rek_rincian_obyek.id', '=', 'kertas_kerja_belanja.rincian_obyek_id')
            ->join('sd_tanggal', 'kertas_kerja_belanja.sd_tanggal_id', '=', 'sd_tanggal.id')
            ->where('sd_tanggal.jenis_pembahasan', '=', 'struktur_murni')
            ->where('sd_tanggal.tanggal', '=', $request->tanggal)
            ->select('rek_jenis.id as jenis_id', 'rek_jenis.kode as kode_jenis', 'rek_jenis.nama_jenis',
                'rek_akun.id as akun_id', 'rek_akun.kode as kode_akun', 'rek_akun.nama_akun',
                'rek_kelompok.id as kelompok_id', 'rek_kelompok.kode as kode_kelompok', 'rek_kelompok.nama_kelompok',
                'kertas_kerja_belanja.nilai')
            ->get();

        if (count($kertasKerjaBelanja) > 0) {
            return $belanja = $this->getFormat($kertasKerjaBelanja);
        } else {
            return $belanja = [];
        }
    }

    protected function kertasKerjaPembiayaan($request)
    {
        $kertasKerjaPembiayaan = DB::table('rek_jenis')
            ->join('rek_kelompok', 'rek_kelompok.id', '=', 'rek_jenis.kelompok_id')
            ->join('rek_akun', 'rek_akun.id', '=', 'rek_kelompok.akun_id')
            ->join('rek_obyek', 'rek_jenis.id', '=', 'rek_obyek.jenis_id')
            ->join('rek_rincian_obyek', 'rek_obyek.id', '=', 'rek_rincian_obyek.obyek_id')
            ->join('kertas_kerja_pembiayaan', 'rek_rincian_obyek.id', '=', 'kertas_kerja_pembiayaan.rincian_obyek_id')
            ->join('sd_tanggal', 'kertas_kerja_pembiayaan.sd_tanggal_id', '=', 'sd_tanggal.id')
            ->where('sd_tanggal.jenis_pembahasan', '=', 'struktur_murni')
            ->where('sd_tanggal.tanggal', '=', $request->tanggal)
            ->select('rek_jenis.id as jenis_id', 'rek_jenis.kode as kode_jenis', 'rek_jenis.nama_jenis',
                'rek_akun.id as akun_id', 'rek_akun.kode as kode_akun', 'rek_akun.nama_akun',
                'rek_kelompok.id as kelompok_id', 'rek_kelompok.kode as kode_kelompok', 'rek_kelompok.nama_kelompok',
                'kertas_kerja_pembiayaan.nilai')
            ->get();

        if (count($kertasKerjaPembiayaan) > 0) {
            return $pembiayaan = $this->getFormat($kertasKerjaPembiayaan);
        } else {
            return $pembiayaan = [];
        }
    }

    protected function kertasKerjaPendapatanPergeseran($tanggal)
    {

        $perubahan = null;

        $pendapatans = DB::select("SELECT DISTINCT tanggal, akun_id, kode_akun, nama_akun, kelompok_id,
            kelompok_kode, nama_kelompok,jenis_id AS jns_id, jenis_kode, nama_jenis,
                (
		            SELECT
			            SUM(nilai)
		            FROM
			            view_kertas_kerja_pendapatan
		            WHERE
			            jenis_id = jns_id
		            AND
			            jenis_pembahasan = 'struktur_murni'
		            AND
			            tanggal = (SELECT MAX(tanggal) FROM sd_tanggal WHERE jenis_pembahasan = 'struktur_murni')
	            ) AS murni,
	            (
		            SELECT
			            SUM(nilai)
		            FROM
			            view_kertas_kerja_pendapatan
		            WHERE
			            jenis_id = jns_id
		            AND
            			jenis_pembahasan = 'struktur_perubahan'
	            ) AS perubahan
            FROM view_kertas_kerja_pendapatan
            WHERE tanggal = '" . $tanggal . "' AND jenis_pembahasan = 'struktur_perubahan'");

        foreach ($pendapatans as $key => $item) {
            if (!isset($perubahan)) {
                $perubahan = [
                    'akun_id' => $item->akun_id,
                    'kode_akun' => $item->kode_akun,
                    'nama_akun' => $item->nama_akun,
                    'nilai_murni' => $item->murni,
                    'nilai_perubahan' => $item->perubahan,
                    'kelompok' => [
                        [
                            'kelompok_id' => $item->kelompok_id,
                            'kelompok_kode' => $item->kelompok_kode,
                            'nama_kelompok' => $item->nama_kelompok,
                            'nilai_murni' => $item->murni,
                            'nilai_perubahan' => $item->perubahan,
                            'jenis' => [
                                [
                                    'jenis_id' => $item->jns_id,
                                    'jenis_kode' => $item->jenis_kode,
                                    'nama_jenis' => $item->nama_jenis,
                                    'nilai_murni' => $item->murni,
                                    'nilai_perubahan' => $item->perubahan
                                ]
                            ]
                        ]
                    ]
                ];
            } else {
                $perubahan['nilai_murni'] = $perubahan['nilai_murni'] + $item->murni;
                $perubahan['nilai_perubahan'] = $perubahan['nilai_perubahan'] + $item->perubahan;

                if (!isset($perubahan['kelompok'])) {
                    $perubahan['kelompok'] = [];
                }

                $checkKelompok = array_search($item->kelompok_id, array_column($perubahan['kelompok'], 'kelompok_id'));

                if (!is_numeric($checkKelompok) && !$checkKelompok) {

                    array_push($perubahan['kelompok'], [
                        'kelompok_id' => $item->kelompok_id,
                        'kelompok_kode' => $item->kelompok_kode,
                        'nama_kelompok' => $item->nama_kelompok,
                        'nilai_murni' => $item->murni,
                        'nilai_perubahan' => $item->perubahan,
                        'jenis' => [
                            [
                                'jenis_id' => $item->jns_id,
                                'jenis_kode' => $item->jenis_kode,
                                'nama_jenis' => $item->nama_jenis,
                                'nilai_murni' => $item->murni,
                                'nilai_perubahan' => $item->perubahan
                            ]
                        ]
                    ]);
                } else {
                    $perubahan['kelompok'][$checkKelompok]['nilai_murni'] = $perubahan['kelompok'][$checkKelompok]['nilai_murni'] + $item->murni;
                    $perubahan['kelompok'][$checkKelompok]['nilai_perubahan'] = $perubahan['kelompok'][$checkKelompok]['nilai_perubahan'] + $item->perubahan;

                    array_push($perubahan['kelompok'][$checkKelompok]['jenis'], [
                        'jenis_id' => $item->jns_id,
                        'jenis_kode' => $item->jenis_kode,
                        'nama_jenis' => $item->nama_jenis,
                        'nilai_murni' => $item->murni,
                        'nilai_perubahan' => $item->perubahan
                    ]);
                }

            }
        }

        return $perubahan;
    }

    protected function kertasKerjaBelanjaPergeseran($tanggal)
    {
        $perubahan = null;

        $belanjas = DB::select("SELECT DISTINCT tanggal, akun_id, kode_akun, nama_akun, kelompok_id,
            kelompok_kode, nama_kelompok,jenis_id AS jns_id, jenis_kode, nama_jenis,
                (
		            SELECT
			            SUM(nilai)
		            FROM
			            view_kertas_kerja_belanja
		            WHERE
			            jenis_id = jns_id
		            AND
			            jenis_pembahasan = 'struktur_murni'
		            AND
			            tanggal = (SELECT MAX(tanggal) FROM sd_tanggal WHERE jenis_pembahasan = 'struktur_murni')
	            ) AS murni,
	            (
		            SELECT
			            SUM(nilai)
		            FROM
			            view_kertas_kerja_belanja
		            WHERE
			            jenis_id = jns_id
		            AND
            			jenis_pembahasan = 'struktur_perubahan'
	            ) AS perubahan
            FROM view_kertas_kerja_belanja
            WHERE tanggal = '" . $tanggal . "' AND jenis_pembahasan = 'struktur_perubahan'");

        foreach ($belanjas as $key => $item) {
            if (!isset($perubahan)) {
                $perubahan = [
                    'akun_id' => $item->akun_id,
                    'kode_akun' => $item->kode_akun,
                    'nama_akun' => $item->nama_akun,
                    'nilai_murni' => $item->murni,
                    'nilai_perubahan' => $item->perubahan,
                    'kelompok' => [
                        [
                            'kelompok_id' => $item->kelompok_id,
                            'kelompok_kode' => $item->kelompok_kode,
                            'nama_kelompok' => $item->nama_kelompok,
                            'nilai_murni' => $item->murni,
                            'nilai_perubahan' => $item->perubahan,
                            'jenis' => [
                                [
                                    'jenis_id' => $item->jns_id,
                                    'jenis_kode' => $item->jenis_kode,
                                    'nama_jenis' => $item->nama_jenis,
                                    'nilai_murni' => $item->murni,
                                    'nilai_perubahan' => $item->perubahan
                                ]
                            ]
                        ]
                    ]
                ];
            } else {
                $perubahan['nilai_murni'] = $perubahan['nilai_murni'] + $item->murni;
                $perubahan['nilai_perubahan'] = $perubahan['nilai_perubahan'] + $item->perubahan;

                if (!isset($perubahan['kelompok'])) {
                    $perubahan['kelompok'] = [];
                }

                $checkKelompok = array_search($item->kelompok_id, array_column($perubahan['kelompok'], 'kelompok_id'));

                if (!is_numeric($checkKelompok) && !$checkKelompok) {

                    array_push($perubahan['kelompok'], [
                        'kelompok_id' => $item->kelompok_id,
                        'kelompok_kode' => $item->kelompok_kode,
                        'nama_kelompok' => $item->nama_kelompok,
                        'nilai_murni' => $item->murni,
                        'nilai_perubahan' => $item->perubahan,
                        'jenis' => [
                            [
                                'jenis_id' => $item->jns_id,
                                'jenis_kode' => $item->jenis_kode,
                                'nama_jenis' => $item->nama_jenis,
                                'nilai_murni' => $item->murni,
                                'nilai_perubahan' => $item->perubahan
                            ]
                        ]
                    ]);
                } else {
                    $perubahan['kelompok'][$checkKelompok]['nilai_murni'] = $perubahan['kelompok'][$checkKelompok]['nilai_murni'] + $item->murni;
                    $perubahan['kelompok'][$checkKelompok]['nilai_perubahan'] = $perubahan['kelompok'][$checkKelompok]['nilai_perubahan'] + $item->perubahan;

                    array_push($perubahan['kelompok'][$checkKelompok]['jenis'], [
                        'jenis_id' => $item->jns_id,
                        'jenis_kode' => $item->jenis_kode,
                        'nama_jenis' => $item->nama_jenis,
                        'nilai_murni' => $item->murni,
                        'nilai_perubahan' => $item->perubahan
                    ]);
                }

            }
        }

        return $perubahan;
    }

    protected function kertasKerjaPembiayaanPergeseran($request)
    {
        $kertasKerjaPembiayaan = DB::table('rek_jenis')
            ->join('rek_kelompok', 'rek_kelompok.id', '=', 'rek_jenis.kelompok_id')
            ->join('rek_akun', 'rek_akun.id', '=', 'rek_kelompok.akun_id')
            ->join('rek_obyek', 'rek_jenis.id', '=', 'rek_obyek.jenis_id')
            ->join('rek_rincian_obyek', 'rek_obyek.id', '=', 'rek_rincian_obyek.obyek_id')
            ->join('kertas_kerja_pembiayaan', 'rek_rincian_obyek.id', '=', 'kertas_kerja_pembiayaan.rincian_obyek_id')
            ->join('sd_tanggal', 'kertas_kerja_pembiayaan.sd_tanggal_id', '=', 'sd_tanggal.id')
            ->where('sd_tanggal.jenis_pembahasan', '=', 'struktur_perubahan')
            ->where('sd_tanggal.tanggal', '=', $request->tanggal)
            ->select('rek_jenis.id as jenis_id', 'rek_jenis.kode as kode_jenis', 'rek_jenis.nama_jenis',
                'rek_akun.id as akun_id', 'rek_akun.kode as kode_akun', 'rek_akun.nama_akun',
                'rek_kelompok.id as kelompok_id', 'rek_kelompok.kode as kode_kelompok', 'rek_kelompok.nama_kelompok',
                'kertas_kerja_pembiayaan.nilai')
            ->get();

        if (count($kertasKerjaPembiayaan) > 0) {
            return $pembiayaan = $this->getFormat($kertasKerjaPembiayaan);
        } else {
            return $pembiayaan = [];
        }
    }

    public function getFormat($queryResult)
    {
        $akun = [
            'akun_id' => $queryResult->first()->akun_id,
            'kode_akun' => $queryResult->first()->kode_akun,
            'nama_akun' => $queryResult->first()->nama_akun
        ];

        $totalNilaiAkun = 0;
        $kelompok = [];

        foreach ($queryResult as $kertas) {
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
            foreach ($queryResult as $rek_jenis) {
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
//        $tanggal = TanggalSumberDana::where('sd_tahun_id', '=', $tahun_id)
//            ->where('jenis_pembahasan', '=', 'struktur_murni')
//            ->orderBy('tanggal', 'desc')
//            ->get();
        $tanggal = TanggalSumberDana::where('sd_tahun_id', '=', $tahun_id)
            ->orderBy('tanggal', 'desc')
            ->select('tanggal')
            ->distinct()
            ->get();
        return $tanggal;
    }

    protected function getLatestTanggalKertasKerja($tahun_id, $jenis_pembahasan)
    {
        $tanggal = TanggalSumberDana::where('sd_tahun_id', '=', $tahun_id)
            ->where('jenis_pembahasan', '=', $jenis_pembahasan)
            ->orderBy('tanggal', 'desc')
            ->first();
        if ($tanggal)
            return $tanggal->id;
        return '';
    }

    public function laporan2020()
    {
        $sumberDanaFix = DataLaporan2020::data();

//        dd($sumberDanaFix);
//        return view('laporan.kertas-kerja.kertas-kerja-2020', compact('sumberDanaFix'));
        $pdf = PDF::loadview('laporan.kertas-kerja.kertas-kerja-2020', compact('sumberDanaFix'));
        return $pdf->setPaper('Legal', 'portrait')->stream('Laporan', '.pdf');

    }

    /**
     * @param $search
     * @param $keyword
     * @param $array
     * @return |null
     */
    protected function searchFor($search, $keyword, $array)
    {
        foreach ($array as $key => $val) {
            if ($val[$keyword] === $search) {
                return $val;
            }
        }
        return null;
    }

    /**
     * @param $array_1
     * @param $array_2
     * @return array
     */
    protected function getPergeseranKelompokFormat($array_1, $array_2)
    {
        $tmpKelompok = [];

        for ($i = 0; $i < count($array_1->kelompok); $i++) {
            $tmpJenis = [];
            $tmpMurni = [];
            $tmpPergeseran = [];
            $pergeseran = $this->searchFor($array_1->kelompok[$i]['kelompok_id'], 'kelompok_id', $array_2->kelompok);

            $jenisMurni = $array_1->kelompok[$i]['jenis'] ? $array_1->kelompok[$i]['jenis'] : [];
            $jenisPergeseran = $pergeseran ? $pergeseran['jenis'] : [];

            if (count($jenisMurni) >= count($jenisPergeseran)) {
                $tmpMurni = $jenisMurni;
                $tmpPergeseran = $this->getPergeseranJenisFormat($jenisMurni, $jenisPergeseran);
            } else {
                $tmpPergeseran = $jenisPergeseran;
                $tmpMurni = $this->getPergeseranJenisFormat($jenisPergeseran, $jenisMurni);
            }

            array_push($tmpKelompok, [
                "kelompok_id" => $array_1->kelompok[$i]['kelompok_id'],
                "kode_kelompok" => $array_1->kelompok[$i]['kode_kelompok'],
                "nama_kelompok" => $array_1->kelompok[$i]['nama_kelompok'],
                'nilai_murni' => $array_1->kelompok[$i]['nilai'],
                'nilai_pergeseran' => $pergeseran ? $pergeseran['nilai'] : 0,
                'jenis_murni' => $tmpMurni,
                'jenis_pergeseran' => $tmpPergeseran,
            ]);


        }

        return $tmpKelompok;
    }

    protected function getPergeseranJenisFormat($array_1, $array_2)
    {
        $tmpArray = [];

        for ($x = 0; $x < count($array_1); $x++) {

            //Cek apakah ada data jenis di array pergeseran
            $check = $this->searchFor($array_1[$x]['jenis_id'], 'jenis_id', $array_2);

            if ($check == null) {
                $check = $array_1[$x];
                $check = array_replace($check, ['nilai' => 0]);
            }

            array_push($tmpArray, $check);
        }

        return $tmpArray;
    }

}
