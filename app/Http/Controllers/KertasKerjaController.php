<?php

namespace App\Http\Controllers;

use App\Http\Resources\BelanjaResource;
use App\Http\Resources\CreateBelanjaResource;
use App\Http\Resources\CreatePemniayaanResource;
use App\Http\Resources\CreatePendapatanResource;
use App\Http\Resources\PemniayaanResource;
use App\Http\Resources\PendapatanResource;
use App\Models\KertasKerjaPendapatan;
use App\Models\KertasKerjaBelanja;
use App\Models\KertasKerjaPembiayaan;
use App\Models\OrganisasiUnit;
use App\Models\RekeningAkun;
use App\Models\RekeningJenis;
use App\Models\RekeningRincianObyek;
use App\Models\TahunRekening;
use App\Models\TahunSumberDana;
use App\Models\TanggalSumberDana;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KertasKerjaController extends AppController
{

    public function rek()
    {
        $rekPendapatans = RekeningJenis::join('rek_kelompok', 'rek_kelompok.id', 'rek_jenis.kelompok_id')
            ->join('rek_akun', 'rek_akun.id', 'rek_kelompok.akun_id')
            ->where('rek_akun.alias', '=', 'pendapatan')
//            ->where('rek_akun.tahun_rekening_id', '=', 20)
            ->select('rek_akun.kode as kode_akun', 'rek_kelompok.kode as kode_kelompok', 'rek_jenis.*')
            ->get();
        return dd($rekPendapatans);
    }

    /*
     * Tahun kertas kerja ----------------------------------------------------------------------------------------------
     */
    public function tahunSumberDana()
    {
        $tahuns = TahunSumberDana::orderBy('tahun', 'desc')->get();
        return view('kertas-kerja.tahun', compact('tahuns'));
    }

    public function fetchTahun()
    {
        $tahuns = TahunSumberDana::orderBy('tahun', 'desc')->get();
        return response()->json(['data' => $tahuns], 200);
    }

    public function storeTahun(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tahun' => 'required|unique:sd_tahun',
        ], [
            'tahun.required' => 'Tahun sumber dana tidak boleh kosong.',
            'tahun.unique' => 'Tahun sumber dana sudah ada.',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'error' => $validator->errors()]);
        }

        $tahun = TahunSumberDana::create($request->all());

        if ($tahun)
            return $this->createdResponse($tahun);

        return $this->storeFailedResponse();
    }

    public function destroyTahun($id)
    {
        $tahun = TahunSumberDana::findOrFail($id);
        $tahun->delete();
        return response()->json(['status' => true, 'message' => 'Berhasil menghapus data tahun sumber dana.'], 200);
    }

    /*
     * End of tahun kertas kerja ---------------------------------------------------------------------------------------
     */

    /*
     * Tanggal Kertas Kerja --------------------------------------------------------------------------------------------
     */

    public function tanggalKertasKerja($tahun_id, $pembahasan)
    {
        $jenisPembahasan = $pembahasan == 'murni' ? 'struktur_murni' : 'struktur_perubahan';

        $tahun = TahunSumberDana::with('tanggal')
            ->where('id', '=', $tahun_id)
            ->firstOrFail();

        $tanggal = TanggalSumberDana::where('sd_tahun_id', '=', $tahun_id);
        if ($pembahasan == 'murni') {
            $tanggal->where('jenis_pembahasan', '=', $jenisPembahasan);
            $title = 'Pembahasan Struktur Murni Tahun ' . $tahun->tahun;
        } else {
            $tanggal->where('jenis_pembahasan', '=', $jenisPembahasan);
            $title = 'Pembahasan Struktur Perubahan Tahun ' . $tahun->tahun;
        }
        $tanggal_kertas_kerja = $tanggal->get();

        return view('kertas-kerja.kertas-kerja', compact('tahun', 'tanggal_kertas_kerja', 'title', 'pembahasan'));
    }
    /*
     * Akhir tanggal kertas kerja
     */

    /*
     * Rekening
     */
    public function tahunRekeningAktif()
    {
        $tahunRek = TahunRekening::where('status', '=', 1)->firstOrFail();
        return $tahunRek;
    }

    public function rekeningPendapatan(Request $request)
    {
        $rekPendapatans = RekeningRincianObyek::with('obyek.jenis.kelompok.akun')
            ->whereHas('obyek.jenis.kelompok.akun', function ($q) {
                $q->where('alias', '=', 'pendapatan')
                    ->where('rek_akun.tahun_rekening_id', '=', $this->tahunRekeningAktif()->id);
            })
            ->where('nama_rincian_obyek', 'like', '%' . $request->nama_rekening . '%')
            ->get();
        return response()->json($rekPendapatans);
    }

    public function rekeningBelanja(Request $request)
    {
        $rekeningBelanjas = RekeningRincianObyek::with('obyek.jenis.kelompok.akun')
            ->whereHas('obyek.jenis.kelompok.akun', function ($q) {
                $q->where('alias', '=', 'belanja')
                    ->where('rek_akun.tahun_rekening_id', '=', $this->tahunRekeningAktif()->id);
            })
            ->where('nama_rincian_obyek', 'like', '%' . $request->nama_rekening . '%')
            ->get();
        return response()->json($rekeningBelanjas);
    }


    public function rekeningPembiayaan(Request $request, $id = null)
    {
        if ($id == null) {
            $rekPendapatans = RekeningRincianObyek::with('obyek.jenis.kelompok.akun')
                ->whereHas('obyek.jenis.kelompok.akun', function ($q) {
                    $q->where('alias', '=', 'pembiayaan')
                        ->where('rek_akun.tahun_rekening_id', '=', $this->tahunRekeningAktif()->id);
                })
                ->where('nama_rincian_obyek', 'like', '%' . $request->nama_rekening . '%')
                ->get();
        } else {
            $rekPendapatans = RekeningRincianObyek::with('obyek.jenis.kelompok.akun')
                ->whereHas('obyek.jenis.kelompok.akun', function ($q) {
                    $q->where('alias', '=', 'pembiayaan')
                        ->where('rek_akun.tahun_rekening_id', '=', $this->tahunRekeningAktif()->id);
                })
                ->where('id', '=', $id)
                ->firstOrFail();
        }

        return response()->json($rekPendapatans);
    }


    /*
     * Pendapatan ------------------------------------------------------------------------------------------------------
     */
    public function fetchKertasKerja($tahun_id, $pembahasan, $tanggal_id)
    {
        $jenisPembahasan = $pembahasan == 'murni' ? 'struktur_murni' : 'struktur_perubahan';

        $tahunRek = TahunRekening::where('status', '=', 1)->firstOrFail();
        $tahun = TahunSumberDana::with([
            'tanggal' => function ($q) use ($jenisPembahasan) {
                $q->where('jenis_pembahasan', '=', $jenisPembahasan);
            }
        ])
            ->whereHas('tanggal', function ($q) use ($jenisPembahasan) {
                $q->where('jenis_pembahasan', '=', $jenisPembahasan);
            })
            ->where('id', '=', $tahun_id)->first();

        $opds = OrganisasiUnit::with('bidang.urusan')->get();

        if ($pembahasan == 'murni') {
            $title = 'Pembahasan Struktur Murni Tahun ' . $tahun->tahun;
        } else {
            $title = 'Pembahasan Struktur Perubahan Tahun ' . $tahun->tahun;
        }

        $pendapatans = KertasKerjaPendapatan::with(['unit'])
            ->where('sd_tanggal_id', '=', $tanggal_id)
            ->groupBy('unit_id')->get();

        return view('kertas-kerja.kertas-kerja-item', compact('tahun', 'opds', 'pendapatans', 'tanggal_id',
            'pembahasan', 'title'));
    }

    public function fetchPendapatanJson($tgl_id)
    {
        $totalSumberDana = KertasKerjaPendapatan::where('sd_tanggal_id', '=', $tgl_id)
            ->sum('nilai');
        $opds = OrganisasiUnit::with('bidang.urusan')->get();
        $pendapatan = KertasKerjaPendapatan::with(['unit', 'jenis'])
            ->where('sd_tanggal_id', '=', $tgl_id)
            ->groupBy('unit_id')
            ->get();
        return response()->json(['data' => PendapatanResource::collection($pendapatan),
            'opd' => $opds, 'totalSumberDana' => $totalSumberDana], 200);
    }

    public function storePendapatan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'rincian_obyek_id' => 'required',
            'nilai' => 'required',
            'unit_id' => 'required',
            'uraian' => 'required',
        ], [
            'rincian_obyek_id.required' => 'Anda belum memilih rekening.',
            'nilai.required' => 'Nilai pendapatan tidak boleh kosong.',
            'unit_id.required' => 'Anda belum memilih OPD.',
            'uraian.required' => 'uraian tidak boleh kosong.',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'error' => $validator->errors()]);
        }

        $pendapatan = KertasKerjaPendapatan::create($request->all());
        $totalSumberDana = KertasKerjaPendapatan::where('sd_tanggal_id', '=', $request->sd_tanggal_id)
            ->sum('nilai');
        if ($pendapatan)

            return $this->createdResponse(new CreatePendapatanResource($pendapatan), $totalSumberDana);

        return $this->storeFailedResponse();
    }

    public function updateNominal(Request $request)
    {
        $itemKertasKerja = KertasKerjaPendapatan::findOrFail($request->uraian_id);
        $itemKertasKerja->nilai = $request->new_nominal;
        $itemKertasKerja->save();

        $totalSumberDana = KertasKerjaPendapatan::where('sd_tanggal_id', '=', $request->sd_tanggal_id)
            ->sum('nilai');

        if ($itemKertasKerja)
            return response()->json(['status' => true, 'message' => 'Berhasil mengubah nominal.', 'totalSumberDana' => $totalSumberDana, 'data' => $itemKertasKerja], 200);

        return $this->storeFailedResponse('Gagal mengubah nominal');
    }

    /*
     * End of pendapatan
     */

    /*
     * Belanja ---------------------------------------------------------------------------------------------------------
     */
    public function fetchBelanja($tanggal_id)
    {
        $totalBelanja = KertasKerjaBelanja::where('sd_tanggal_id', '=', $tanggal_id)->sum('nilai');
        $totalBelanjaPembiayaan = KertasKerjaBelanja::where('sd_tanggal_id', '=', $tanggal_id)->sum('nilai_pembiayaan');
        $pendapatan = KertasKerjaPendapatan::where('sd_tanggal_id', '=', $tanggal_id)
            ->sum('nilai');
        $totalPembiayaan = KertasKerjaPembiayaan::where('sd_tanggal_id', '=', $tanggal_id)->sum('nilai');

        if ($totalBelanja > $pendapatan) {
            $totalSumberDana = 0;
            $totalPembiayaan = $totalPembiayaan - $totalBelanjaPembiayaan;
        } else {
            $totalSumberDana = $pendapatan - $totalBelanja;
        }

        $opds = OrganisasiUnit::with('bidang.urusan')->get();
        $belanja = KertasKerjaBelanja::with(['unit'])
            ->where('sd_tanggal_id', '=', $tanggal_id)
            ->groupBy('unit_id')
            ->get();
        return response()->json([
            'data' => BelanjaResource::collection($belanja),
            'opd' => $opds,
            'totalSumberDana' => $totalSumberDana,
            'totalBelanja' => $totalBelanja,
            'totalPembiayaan' => $totalPembiayaan], 200);
    }

    public function storeBelanja(Request $request)
    {

        $nilai_pembiayaan = 0;
        $data = [];

        $validator = Validator::make($request->all(), [
            'rincian_obyek_id' => 'required',
            'nilai_belanja' => 'required',
            'unit_id' => 'required',
            'uraian_belanja' => 'required',
        ], [
            'rincian_obyek_id.required' => 'Anda belum memilih rekening.',
            'nilai_belanja.required' => 'Nilai pendapatan tidak boleh kosong.',
            'unit_id.required' => 'Anda belum memilih OPD.',
            'uraian_belanja.required' => 'uraian tidak boleh kosong.',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'error' => $validator->errors()]);
        }

        if ($request->has('pembiayaan_checkbox')) {
            $nilai_pembiayaan = $request->nilai_belanja - $request->total_pendapatan;
            $data = [
                'rincian_obyek_id' => $request->rincian_obyek_id,
                'nilai' => $request->nilai_belanja,
                'nilai_pembiayaan' => $nilai_pembiayaan,
                'pembiayaan_id' => $request->pembiayaan_id,
                'pendapatan_id' => $request->pendapatan_id,
                'sd_tanggal_id' => $request->sd_tanggal_id,
                'unit_id' => $request->unit_id,
                'uraian' => $request->uraian_belanja,
            ];
        } else {
            $data = [
                'rincian_obyek_id' => $request->rincian_obyek_id,
                'nilai' => $request->nilai_belanja,
                'pendapatan_id' => $request->pendapatan_id,
                'sd_tanggal_id' => $request->sd_tanggal_id,
                'unit_id' => $request->unit_id,
                'uraian' => $request->uraian_belanja,
            ];
        }

        $belanja = KertasKerjaBelanja::create($data);

        $totalBelanja = KertasKerjaBelanja::where('sd_tanggal_id', '=', $request->sd_tanggal_id)->sum('nilai');
        $totalBelanjaPembiayaan = KertasKerjaBelanja::where('sd_tanggal_id', '=', $request->sd_tanggal_id)->sum('nilai_pembiayaan');
        $pendapatan = KertasKerjaPendapatan::where('sd_tanggal_id', '=', $request->sd_tanggal_id)
            ->sum('nilai');
        $totalPembiayaan = KertasKerjaPembiayaan::where('sd_tanggal_id', '=', $request->sd_tanggal_id)->sum('nilai');

        if ($totalBelanja > $pendapatan) {
            $totalSumberDana = 0;
            $totalPembiayaan = $totalPembiayaan - $totalBelanjaPembiayaan;
        } else {
            $totalSumberDana = $pendapatan - $totalBelanja;
        }

        if ($belanja)
            return response()->json([
                'status' => true,
                'message' => 'Berhasil menyimpan data.',
                'data' => new CreateBelanjaResource($belanja),
                'totalSumberDana' => $totalSumberDana,
                'totalBelanja' => $totalBelanja,
                'totalPembiayaan' => $totalPembiayaan], 200);

        return $this->storeFailedResponse();
    }

    public function updateNominalBelanja(Request $request)
    {
        $itemKertasKerja = KertasKerjaBelanja::findOrFail($request->uraian_id);

        $selisihNilai = 0;
        $selisihNilaiSetelahDikurangiPembiayaan = 0;

        if (floatval($request->new_nominal) > $itemKertasKerja->nilai) {
            //nilai baru lebih besar
            $selisihNilai = floatval($request->new_nominal) - $itemKertasKerja->nilai;

            //jikan nilai lebih besar dari total pendapatan, maka tambahkan dari pembiayaan
            if ($selisihNilai > $request->total_pendapatan) {
                $itemKertasKerja->nilai_pembiayaan = floatval($itemKertasKerja->nilai_pembiayaan + $selisihNilai);
                $itemKertasKerja->nilai_pembiayaan = $itemKertasKerja->nilai_pembiayaan - floatval($request->total_pendapatan);
            }

            $itemKertasKerja->pembiayaan_id = $request->pembiayaan_id;

        } else if (floatval($request->new_nominal) < $itemKertasKerja->nilai) {
            //nilai baru lebih kecil
            $selisihNilai = $itemKertasKerja->nilai - floatval($request->new_nominal);

            if ($itemKertasKerja->pembiayaan_id != '') {
                if ($selisihNilai < $itemKertasKerja->nilai_pembiayaan) {
                    $itemKertasKerja->nilai_pembiayaan = $itemKertasKerja->nilai_pembiayaan - $selisihNilai;
                } else if ($selisihNilai > $itemKertasKerja->nilai_pembiayaan) {
                    $selisihNilaiSetelahDikurangiPembiayaan = $selisihNilai - $itemKertasKerja->nilai_pembiayaan;
                    $itemKertasKerja->nilai_pembiayaan = 0;
                }
            }
        }

        //simpan nilai baru belanja
        $itemKertasKerja->nilai = $request->new_nominal;
        $itemKertasKerja->save();

        $totalBelanja = KertasKerjaBelanja::where('sd_tanggal_id', '=', $request->sd_tanggal_id)->sum('nilai');

        $totalBelanjaPembiayaan = KertasKerjaBelanja::where('sd_tanggal_id', '=', $request->sd_tanggal_id)->sum('nilai_pembiayaan');
        $pendapatan = KertasKerjaPendapatan::where('sd_tanggal_id', '=', $request->sd_tanggal_id)
            ->sum('nilai');
        $totalPembiayaan = KertasKerjaPembiayaan::where('sd_tanggal_id', '=', $request->sd_tanggal_id)->sum('nilai');

        if ($totalBelanja > $pendapatan) {
            $totalSumberDana = 0;
            $totalPembiayaan = $totalPembiayaan - $totalBelanjaPembiayaan;
        } else {
            $totalSumberDana = $pendapatan - $totalBelanja;
        }

        if ($itemKertasKerja)
            return response()->json([
                'status' => true,
                'message' => 'Berhasil mengubah nominal.',
                'totalSumberDana' => $totalSumberDana,
                'totalBelanja' => $totalBelanja,
                'totalPembiayaan' => $totalPembiayaan,
                'data' => $itemKertasKerja], 200);

        return $this->storeFailedResponse('Gagal mengubah nominal');
    }

    /*
     * End of belanja --------------------------------------------------------------------------------------------------
     */

    /*
     * Pembiayaan ------------------------------------------------------------------------------------------------------
     */
    public function fetchPembiayaan($tanggal_id)
    {
        $totalPembiayaan = KertasKerjaPembiayaan::where('sd_tanggal_id', '=', $tanggal_id)->sum('nilai');
        $pendapatan = KertasKerjaPendapatan::where('sd_tanggal_id', '=', $tanggal_id)
            ->sum('nilai');
        $totalSumberDana = $pendapatan + $totalPembiayaan;
        $opds = OrganisasiUnit::with('bidang.urusan')->get();
        $pembiayaan = KertasKerjaPembiayaan::with(['unit'])
            ->where('sd_tanggal_id', '=', $tanggal_id)
            ->groupBy('unit_id')
            ->get();
        return response()->json([
            'data' => PemniayaanResource::collection($pembiayaan),
            'opd' => $opds,
            'totalSumberDana' => $totalSumberDana,
            'totalPembiayaan' => $totalPembiayaan], 200);
    }

    public function storePembiayaan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'rincian_obyek_id' => 'required',
            'nilai' => 'required',
            'unit_id' => 'required',
            'uraian' => 'required',
        ], [
            'rincian_obyek_id.required' => 'Anda belum memilih rekening.',
            'nilai.required' => 'Nilai pendapatan tidak boleh kosong.',
            'unit_id.required' => 'Anda belum memilih OPD.',
            'uraian.required' => 'uraian tidak boleh kosong.',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'error' => $validator->errors()]);
        }

        $pembiayaan = KertasKerjaPembiayaan::create($request->all());
        $totalPembiayaan = KertasKerjaPembiayaan::where('sd_tanggal_id', '=', $request->sd_tanggal_id)->sum('nilai');
        $totalPendapatan = KertasKerjaPendapatan::where('sd_tanggal_id', '=', $request->sd_tanggal_id)
            ->sum('nilai');
        $totalSumberDana = $totalPendapatan - $totalPembiayaan;
        if ($pembiayaan)
            return response()->json([
                'status' => true,
                'message' => 'Berhasil menyimpan data.',
                'data' => new CreatePemniayaanResource($pembiayaan),
                'totalSumberDana' => $totalSumberDana,
                'totalPembiayaan' => $totalPembiayaan], 200);

        return $this->storeFailedResponse();
    }

    public function updateNominalPembiayaan(Request $request)
    {
        $itemKertasKerja = KertasKerjaPembiayaan::findOrFail($request->uraian_id);
        $itemKertasKerja->nilai = $request->new_nominal;
        $itemKertasKerja->save();

        $totalPembiayaan = KertasKerjaPembiayaan::where('sd_tanggal_id', '=', $request->sd_tanggal_id)->sum('nilai');
        $totalPendapatan = KertasKerjaPendapatan::where('sd_tanggal_id', '=', $request->sd_tanggal_id)
            ->sum('nilai');
        $totalSumberDana = $totalPendapatan - $totalPembiayaan;

        if ($itemKertasKerja)
            return response()->json([
                'status' => true,
                'message' => 'Berhasil mengubah nominal.',
                'totalSumberDana' => $totalSumberDana,
                'totalPembiayaan' => $totalPembiayaan,
                'data' => $itemKertasKerja], 200);

        return $this->storeFailedResponse('Gagal mengubah nominal');
    }

    /*
     * End of pembiayaan -----------------------------------------------------------------------------------------------
     */

    /*
     * Kunci Struktur Murni --------------------------------------------------------------------------------------------
     */

    public function kunciStrukturMurni($tahunSumberDanaId)
    {
        $sumberDana = TahunSumberDana::findOrFail($tahunSumberDanaId);

        $sumberDana->status_murni = TahunSumberDana::strukturMurniFix();
        $sumberDana->save();

        return response()->json(['status' => true, 'message' => 'Berhasil mengunci struktur murni.'], 200);
    }

    public function bukaStrukturMurni($tahunSumberDanaId)
    {
        $sumberDana = TahunSumberDana::findOrFail($tahunSumberDanaId);

        $sumberDana->status_murni = TahunSumberDana::pembahasanStrukturMurni();
        $sumberDana->save();

        return response()->json(['status' => true, 'message' => 'Berhasil membuka struktur murni.'], 200);
    }

    /*
     * End of Kunci Struktur Murni
     */


}
