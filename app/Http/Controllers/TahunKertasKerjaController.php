<?php

namespace App\Http\Controllers;

use App\Models\RekeningJenis;
use App\Models\TahunSumberDana;
use App\Models\OrganisasiSubUnit;
use App\Models\OrganisasiUnit;
use App\Models\OrganisasiUrusan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TahunKertasKerjaController extends AppController
{
    public function index()
    {
        return view('kertas-kerja.tahun');
    }

    public function fetchKertasKerja()
    {
        $tahuns = TahunSumberDana::orderBy('tahun', 'desc')->get();
        return response()->json(['data' => $tahuns], 200);
    }

    public function store(Request $request)
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

    public function destroy($id)
    {
        $tahun = TahunSumberDana::findOrFail($id);
        $tahun->delete();
        return response()->json(['status' => true, 'message' => 'Berhasil menghapus data tahun sumber dana.'], 200);
    }

    public function rencanaAnggaran($sb_tahun_id)
    {
        $pendapatan = function ($q) {
            $q->where('alias', '=', 'pendapatan');
        };

        $belanja = function ($q) {
            $q->where('alias', '=', 'belanja');
        };

        $tahun = TahunSumberDana::findOrFail($sb_tahun_id);
        $opds = OrganisasiUnit::with('bidang.urusan')->get();
        $pendapatans = RekeningJenis::with(['kelompok', 'kelompok.akun'])
            ->whereHas('kelompok.akun', $pendapatan)->get();
        $belanjas = RekeningJenis::with(['kelompok', 'kelompok.akun'])
            ->whereHas('kelompok.akun', $belanja)->get();
        return view('kertas-kerja.kertas-kerja', compact('tahun', 'opds', 'pendapatans', 'belanjas'));
    }
}
