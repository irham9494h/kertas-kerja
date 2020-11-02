<?php

namespace App\Http\Controllers;

use FontLib\Table\Type\kern;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
//        $tanggal = '2020_09_11';
//
//        $pendapatans = DB::select("SELECT DISTINCT tanggal, akun_id, nama_akun, kelompok_id, nama_kelompok,
//            jenis_id AS jns_id, nama_jenis,
//                (
//		            SELECT
//			            SUM(nilai)
//		            FROM
//			            view_kertas_kerja_pendapatan
//		            WHERE
//			            jenis_id = jns_id
//		            AND
//			            jenis_pembahasan = 'struktur_murni'
//		            AND
//			            tanggal = (SELECT MAX(tanggal) FROM sd_tanggal WHERE jenis_pembahasan = 'struktur_murni')
//	            ) AS murni,
//	            (
//		            SELECT
//			            SUM(nilai)
//		            FROM
//			            view_kertas_kerja_pendapatan
//		            WHERE
//			            jenis_id = jns_id
//		            AND
//            			jenis_pembahasan = 'struktur_perubahan'
//	            ) AS perubahan
//            FROM view_kertas_kerja_pendapatan
//            WHERE tanggal = '" . $tanggal . "'");
//
//        $perubahan = null
//
//        foreach ($pendapatans as $key => $item) {
//            if (!isset($perubahan)) {
//                $perubahan = [
//                    'akun_id' => $item->akun_id,
//                    'nama_akun' => $item->nama_akun,
//                    'nilai_murni' => $item->murni,
//                    'nilai_perubahan' => $item->perubahan,
//                    'kelompok' => [
//                        [
//                            'kelompok_id' => $item->kelompok_id,
//                            'nama_kelompok' => $item->nama_kelompok,
//                            'nilai_murni' => $item->murni,
//                            'nilai_perubahan' => $item->perubahan,
//                            'jenis' => [
//                                [
//                                    'jenis_id' => $item->jns_id,
//                                    'nama_jenis' => $item->nama_jenis,
//                                    'nilai_murni' => $item->murni,
//                                    'nilai_perubahan' => $item->perubahan
//                                ]
//                            ]
//                        ]
//                    ]
//                ];
//            } else {
//                if (!isset($perubahan['kelompok'])) {
//                    $perubahan['kelompok'] = [];
//                }
//
//                $checkKelompok = array_search($item->kelompok_id, array_column($perubahan['kelompok'], 'kelompok_id'));
//
//                if (!is_numeric($checkKelompok) && !$checkKelompok) {
//                    array_push($perubahan['kelompok'], [
//                        'kelompok_id' => $item->kelompok_id,
//                        'nama_kelompok' => $item->nama_kelompok,
//                        'nilai_murni' => $item->murni,
//                        'nilai_perubahan' => $item->perubahan,
//                        'jenis' => [
//                            [
//                                'jenis_id' => $item->jns_id,
//                                'nama_jenis' => $item->nama_jenis,
//                                'nilai_murni' => $item->murni,
//                                'nilai_perubahan' => $item->perubahan
//                            ]
//                        ]
//                    ]);
//                } else {
//                    array_push($perubahan['kelompok'][$checkKelompok]['jenis'], [
//                        'jenis_id' => $item->jns_id,
//                        'nama_jenis' => $item->nama_jenis,
//                        'nilai_murni' => $item->murni,
//                        'nilai_perubahan' => $item->perubahan
//                    ]);
//                }
//
//            }
//        }
        return view('home');
    }
}
