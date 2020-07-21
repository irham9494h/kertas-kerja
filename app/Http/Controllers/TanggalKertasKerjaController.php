<?php

namespace App\Http\Controllers;

use App\Helper\TanggalKertasKerjaHelper;
use App\Http\Resources\CreatePendapatanResource;
use App\Http\Resources\PendapatanResource;
use App\Models\KertasKerjaPendapatan;
use App\Models\KertasKerjaBelanja;
use App\Models\KertasKerjaPembiayaan;
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

        $jenisPembahasan = $request->jenis_pembahasan == 'murni' ? 'struktur_murni' : 'struktur_perubahan';

        $count = TanggalSumberDana::where('sd_tahun_id', '=', $request->sb_tahun_id)
            ->where('jenis_pembahasan', '=', $jenisPembahasan)
            ->count();

//        return response()->json(TanggalKertasKerjaHelper::checkIfDateIsLowerThanOtherDate($request->all()));

        if ($count > 0) {
            if (TanggalKertasKerjaHelper::checkIfDateIsLowerThanOtherDate($request->all())) {
                $latestDate = TanggalSumberDana::where('sd_tahun_id', '=', $request->sb_tahun_id)
                    ->where('jenis_pembahasan', '=', $jenisPembahasan)
                    ->latest()->first();

                $pendapatan = KertasKerjaPendapatan::where('sd_tanggal_id', '=', $latestDate->id)
                    ->get();

                $belanja = KertasKerjaBelanja::where('sd_tanggal_id', '=', $latestDate->id)
                    ->get();

                $pembiayaan = KertasKerjaPembiayaan::where('sd_tanggal_id', '=', $latestDate->id)
                    ->get();

                if (count($pendapatan) > 0) {
                    $data = [];

                    $tanggal = TanggalSumberDana::create([
                        'tanggal' => Carbon::createFromFormat('d/m/Y', $request->tanggal)->format('Y-m-d'),
                        'sd_tahun_id' => $request->sb_tahun_id,
                        'jenis_pembahasan' => $jenisPembahasan
                    ]);

                    foreach ($pendapatan as $item) {
                        $data = [
                            'sd_tanggal_id' => $tanggal->id,
                            'unit_id' => $item->unit_id,
                            'rincian_obyek_id' => $item->rincian_obyek_id,
                            'uraian' => $item->uraian,
                            'nilai' => $item->nilai
                        ];
                        $kertasKerja = KertasKerjaPendapatan::create($data);
                    }

                    foreach ($belanja as $item) {
                        $data = [
                            'sd_tanggal_id' => $tanggal->id,
                            'unit_id' => $item->unit_id,
                            'rincian_obyek_id' => $item->rincian_obyek_id,
                            'uraian' => $item->uraian,
                            'nilai' => $item->nilai,
                            'pendapatan_id' => $item->pendapatan_id,
                            'pembiayaan_id' => $item->pembiayaan_id,
                            'nilai_pembiayaan' => $item->nilai_pembiayaan,
                        ];
                        $kertasKerja = KertasKerjaBelanja::create($data);
                    }

                    foreach ($pembiayaan as $item) {
                        $data = [
                            'sd_tanggal_id' => $tanggal->id,
                            'unit_id' => $item->unit_id,
                            'rincian_obyek_id' => $item->rincian_obyek_id,
                            'uraian' => $item->uraian,
                            'nilai' => $item->nilai,
                        ];
                        $kertasKerja = KertasKerjaPembiayaan::create($data);
                    }

                    return $this->createdResponse($tanggal);
                }
                return $this->storeFailedResponse('Item pendapatan pada tanggal sebelumnya masih kosong!.');
            }
            return $this->dateInvalid();
        } else {
            $tanggal = TanggalSumberDana::create([
                'tanggal' => Carbon::createFromFormat('d/m/Y', $request->tanggal)->format('Y-m-d'),
                'sd_tahun_id' => $request->sb_tahun_id,
                'jenis_pembahasan' => $jenisPembahasan
            ]);
        }

        if ($tanggal)
            return $this->createdResponse($tanggal);

        return $this->storeFailedResponse();
    }

    public function destroy($id)
    {
        $temp = '';
        $tanggal = TanggalSumberDana::findOrFail($id);
        $temp = Carbon::createFromFormat('Y-m-d', $tanggal->tanggal)->format('d/m/Y');

        if (KertasKerjaPendapatan::where('sd_tanggal_id', '=', $id)->count() > 0)
            return response()->json(['status' => false, 'message' => 'Gagal menghapus tanggal kertas kerja, tanggal kertas kerja tidak kosong.'], 200);

        $tanggal->delete();
        return response()->json(['status' => true, 'message' => 'Berhasil menghapus data kertas kerja tanggal ' . $temp . '.'], 200);
    }
}
