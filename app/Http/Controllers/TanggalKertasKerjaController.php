<?php

namespace App\Http\Controllers;

use App\Helper\TanggalKertasKerjaHelper;
use App\Http\Resources\CreatePendapatanResource;
use App\Http\Resources\PendapatanResource;
use App\Models\KertasKerja;
use App\Models\KertasKerjaBelanja;
use App\Models\OrganisasiUnit;
use App\Models\TahunSumberDana;
use App\Models\TanggalSumberDana;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TanggalKertasKerjaController extends AppController
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tanggal' => 'required|unique:sd_tanggal',
        ], [
            'tanggal.required' => 'Tanggal sumber dana tidak boleh kosong.',
            'tanggal.unique' => 'Tanggal sumber dana sudah ada.',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'error' => $validator->errors()]);
        }

        $count = TanggalSumberDana::where('sd_tahun_id', '=', $request->sb_tahun_id)->count();

        if ($count > 0) {
            if (TanggalKertasKerjaHelper::checkIfDateIsLowerThanOtherDate($request->all())) {
                $latestDate = TanggalSumberDana::where('sd_tahun_id', '=', $request->sb_tahun_id)
                    ->latest()->first();
                $items = KertasKerja::where('sd_tanggal_id', '=', $latestDate->id)
                    ->where('jenis_item', '=', 'pendapatan')
                    ->get();

                $belanja = KertasKerjaBelanja::where('sd_tanggal_id', '=', $latestDate->id)
                    ->get();

                if (count($items) > 0) {
                    $data = [];

                    $tanggal = TanggalSumberDana::create([
                        'tanggal' => Carbon::createFromFormat('d/m/Y', $request->tanggal)->format('Y-m-d'),
                        'sd_tahun_id' => $request->sb_tahun_id
                    ]);

                    foreach ($items as $item) {
                        $data = [
                            'sd_tanggal_id' => $tanggal->id,
                            'unit_id' => $item->unit_id,
                            'jenis_id' => $item->jenis_id,
                            'uraian' => $item->uraian,
                            'nilai' => $item->nilai,
                            'jenis_item' => 'pendapatan'
                        ];
                        $kertasKerja = KertasKerja::create($data);
                    }

                    foreach ($belanja as $item) {
                        $data = [
                            'sd_tanggal_id' => $tanggal->id,
                            'unit_id' => $item->unit_id,
                            'jenis_id' => $item->jenis_id,
                            'uraian' => $item->uraian,
                            'nilai' => $item->nilai,
                            'pendapatan_id' => $item->pendapatan_id
                        ];
                        $kertasKerja = KertasKerjaBelanja::create($data);
                    }

                    return $this->createdResponse($tanggal);
                }
                return $this->storeFailedResponse('Item pendapatan pada tanggal sebelumnya masih kosong!.');
            }
            return $this->dateInvalid();
        } else {
            $tanggal = TanggalSumberDana::create([
                'tanggal' => Carbon::createFromFormat('d/m/Y', $request->tanggal)->format('Y-m-d'),
                'sd_tahun_id' => $request->sb_tahun_id
            ]);
        }

        if ($tanggal)
            return $this->createdResponse($tanggal);

        return $this->storeFailedResponse();
    }

    public function fetchKertasKerja($sb_tahun_id, $tanggal_id = null)
    {
        if ($tanggal_id != null){

        }
        return view('kertas-kerja.kertas-kerja-item', compact('tahun'));
//        return response()->json(['data' => $tanggal], 200);
    }

    public function destroy($id)
    {
        $temp = '';
        $tanggal = TanggalSumberDana::findOrFail($id);
        $temp = Carbon::createFromFormat('Y-m-d', $tanggal->tanggal)->format('d/m/Y');

        if (KertasKerja::where('sd_tanggal_id', '=', $id)->count() > 0)
            return response()->json(['status' => false, 'message' => 'Gagal menghapus tanggal kertas kerja, tanggal kertas kerja tidak kosong.'], 200);

        $tanggal->delete();
        return response()->json(['status' => true, 'message' => 'Berhasil menghapus data kertas kerja tanggal ' . $temp . '.'], 200);
    }

    /*
     * Pendapatan ----------------------------------------------------------------------
     */

    public function fetchPendapatan($tgl_id)
    {
        $opds = OrganisasiUnit::with('bidang.urusan')->get();
        $pendapatan = KertasKerja::with(['unit'])
            ->where('sd_tanggal_id', '=', $tgl_id)
            ->where('jenis_item', '=', 'pendapatan')
            ->groupBy('unit_id')
            ->get();
        return response()->json(['data' => PendapatanResource::collection($pendapatan), 'opd' => $opds], 200);
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
            'uraian.unique' => 'uraian tidak boleh kosong.',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'error' => $validator->errors()]);
        }

        $request = $request->merge(['jenis_item' => 'pendapatan']);

        $pendapatan = KertasKerja::create($request->all());

        if ($pendapatan)
            return $this->createdResponse(new CreatePendapatanResource($pendapatan));

        return $this->storeFailedResponse();
    }

    public function updateNominal(Request $request)
    {

        $itemKertasKerja = KertasKerja::findOrFail($request->uraian_id);
        $itemKertasKerja->nilai = $request->new_nominal;
        $itemKertasKerja->save();

        if ($itemKertasKerja)
            return $this->successResponse($itemKertasKerja, 'Berhasil mengubah nominal');

        return $this->storeFailedResponse('Gagal mengubah nominal');
    }

    /*
     * End of pendapatan ---------------------------------------------------------------
     */

    /*
     * Belanja -------------------------------------------------------------------------
     */

    public function fetchBelanja($tgl_id)
    {
        $opds = OrganisasiUnit::with('bidang.urusan')->get();
        $belanja = KertasKerja::with(['unit'])
            ->where('sd_tanggal_id', '=', $tgl_id)
            ->where('jenis_item', '=', 'belanja')
            ->groupBy('unit_id')
            ->get();
        return response()->json(['opd' => $opds], 200);
    }

    public function storeBelanja(Request $request)
    {

    }

    public function updateNominalBelanja(Request $request)
    {

    }

    /*
     * End of belanja ------------------------------------------------------------------
     */
}
