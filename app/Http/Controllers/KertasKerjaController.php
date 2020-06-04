<?php

namespace App\Http\Controllers;

use App\Http\Resources\BelanjaResource;
use App\Http\Resources\CreateBelanjaResource;
use App\Http\Resources\CreatePendapatanResource;
use App\Http\Resources\PendapatanResource;
use App\Models\KertasKerja;
use App\Models\KertasKerjaBelanja;
use App\Models\OrganisasiUnit;
use App\Models\RekeningAkun;
use App\Models\RekeningJenis;
use App\Models\TahunSumberDana;
use App\Models\TanggalSumberDana;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KertasKerjaController extends AppController
{
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

    public function tanggalKertasKerja($tahun_id)
    {
        $tahun = TahunSumberDana::with('tanggal')->where('id', '=', $tahun_id)->first();
        return view('kertas-kerja.kertas-kerja', compact('tahun'));
    }

    public function fetchTanggalKertasKerja()
    {

    }

    /*
     * Akhir tanggal kertas kerja
     */

    /*
     * Pendapatan ------------------------------------------------------------------------------------------------------
     */
    public function fetchPendapatan($tahun_id, $tanggal_id)
    {
        $tahun = TahunSumberDana::with('tanggal')->where('id', '=', $tahun_id)->first();
        $opds = OrganisasiUnit::with('bidang.urusan')->get();
        $rekPendapatans = RekeningJenis::join('rek_kelompok', 'rek_kelompok.id', 'rek_jenis.kelompok_id')
            ->join('rek_akun', 'rek_akun.id', 'rek_kelompok.akun_id')
            ->where('rek_akun.alias', '=', 'pendapatan')
            ->select('rek_akun.kode as kode_akun', 'rek_kelompok.kode as kode_kelompok', 'rek_jenis.*')
            ->get();
        $rekBelanjas = RekeningJenis::join('rek_kelompok', 'rek_kelompok.id', 'rek_jenis.kelompok_id')
            ->join('rek_akun', 'rek_akun.id', 'rek_kelompok.akun_id')
            ->where('rek_akun.alias', '=', 'belanja')
            ->select('rek_akun.kode as kode_akun', 'rek_kelompok.kode as kode_kelompok', 'rek_jenis.*')
            ->get();
        $pendapatans = KertasKerja::with(['unit'])
            ->where('sd_tanggal_id', '=', $tanggal_id)->where('jenis_item', '=', 'pendapatan')
            ->groupBy('unit_id')->get();
        return view('kertas-kerja.kertas-kerja-item', compact('tahun', 'opds', 'pendapatans', 'tanggal_id',
            'rekPendapatans', 'rekBelanjas'));
    }

    public function fetchPendapatanJson($tgl_id)
    {
        $totalSumberDana = KertasKerja::where('sd_tanggal_id', '=', $tgl_id)
            ->where('jenis_item', '=', 'pendapatan')->sum('nilai');
        $opds = OrganisasiUnit::with('bidang.urusan')->get();
        $pendapatan = KertasKerja::with(['unit', 'jenis'])
            ->where('sd_tanggal_id', '=', $tgl_id)
            ->where('jenis_item', '=', 'pendapatan')
            ->groupBy('unit_id')
            ->get();
//        return dd($pendapatan);
        return response()->json(['data' => PendapatanResource::collection($pendapatan),
            'opd' => $opds, 'totalSumberDana' => $totalSumberDana], 200);
    }

    public function storePendapatan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'jenis_id' => 'required',
            'nilai' => 'required',
            'unit_id' => 'required',
            'uraian' => 'required',
        ], [
            'jenis_id.required' => 'Anda belum memilih rekening.',
            'nilai.required' => 'Nilai pendapatan tidak boleh kosong.',
            'unit_id.required' => 'Anda belum memilih OPD.',
            'uraian.required' => 'uraian tidak boleh kosong.',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'error' => $validator->errors()]);
        }

        $request = $request->merge(['jenis_item' => 'pendapatan']);

        $pendapatan = KertasKerja::create($request->all());
        $totalSumberDana = KertasKerja::where('sd_tanggal_id', '=', $request->sd_tanggal_id)
            ->where('jenis_item', '=', 'pendapatan')->sum('nilai');
        if ($pendapatan)
//            return $this->createdResponse($request->all(), $totalSumberDana);
            return $this->createdResponse(new CreatePendapatanResource($pendapatan), $totalSumberDana);

        return $this->storeFailedResponse();
    }

    public function updateNominal(Request $request)
    {
        $itemKertasKerja = KertasKerja::findOrFail($request->uraian_id);
        $itemKertasKerja->nilai = $request->new_nominal;
        $itemKertasKerja->save();

        $totalSumberDana = KertasKerja::where('sd_tanggal_id', '=', $request->sd_tanggal_id)
            ->where('jenis_item', '=', 'pendapatan')->sum('nilai');

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
        $pendapatan = KertasKerja::where('jenis_item', '=', 'pendapatan')->where('sd_tanggal_id', '=', $tanggal_id)
            ->sum('nilai');
        $totalSumberDana = $pendapatan - $totalBelanja;
        $opds = OrganisasiUnit::with('bidang.urusan')->get();
        $belanja = KertasKerjaBelanja::with(['unit'])
            ->where('sd_tanggal_id', '=', $tanggal_id)
            ->groupBy('unit_id')
            ->get();
        return response()->json([
            'data' => BelanjaResource::collection($belanja),
            'opd' => $opds,
            'totalSumberDana' => $totalSumberDana,
            'totalBelanja' => $totalBelanja], 200);
    }

    public function storeBelanja(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'jenis_id' => 'required',
            'nilai' => 'required',
            'unit_id' => 'required',
            'uraian' => 'required',
        ], [
            'jenis_id.required' => 'Anda belum memilih rekening.',
            'nilai.required' => 'Nilai pendapatan tidak boleh kosong.',
            'unit_id.required' => 'Anda belum memilih OPD.',
            'uraian.required' => 'uraian tidak boleh kosong.',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'error' => $validator->errors()]);
        }

        $belanja = KertasKerjaBelanja::create($request->all());
        $totalBelanja = KertasKerjaBelanja::where('sd_tanggal_id', '=', $request->sd_tanggal_id)->sum('nilai');
        $totalSumberDana = KertasKerja::where('sd_tanggal_id', '=', $request->sd_tanggal_id)
            ->where('jenis_item', '=', 'pendapatan')->sum('nilai');
        $totalSumberDana = $totalSumberDana - $totalBelanja;
        if ($belanja)
            return response()->json([
                'status' => true,
                'message' => 'Berhasil menyimpan data.',
                'data' => new CreateBelanjaResource($belanja),
                'totalSumberDana' => $totalSumberDana,
                'totalBelanja' => $totalBelanja], 200);

        return $this->storeFailedResponse();
    }

    /*
     * End of belanja --------------------------------------------------------------------------------------------------
     */

    /*
     * Pembiayaan ------------------------------------------------------------------------------------------------------
     */

    /*
     * End of pembiayaan -----------------------------------------------------------------------------------------------
     */

}
