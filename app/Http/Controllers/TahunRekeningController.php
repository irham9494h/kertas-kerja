<?php

namespace App\Http\Controllers;

use App\Models\TahunRekening;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TahunRekeningController extends AppController
{
    public function index()
    {
        $tahuns = TahunRekening::orderBy('tahun', 'desc')->get();
        return view('tahun-rekening', compact('tahuns'));
    }

    public function fetch()
    {
        $tahuns = TahunRekening::orderBy('tahun', 'desc')->get();
        return response()->json(['data' => $tahuns], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tahun' => 'required|unique:tahun_rekening',
        ], [
            'tahun.required' => 'Tahun rekening tidak boleh kosong.',
            'tahun.unique' => 'Tahun rekening sudah ada.',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'error' => $validator->errors()]);
        }

        $tahun = TahunRekening::create($request->all());

        if ($tahun)
            return $this->createdResponse($tahun);

        return $this->storeFailedResponse();
    }

    public function get($id)
    {

    }

    public function update(Request $request, $id)
    {
        $tahun = TahunRekening::findOrFail($id);
        $tahun->update($request->all());

        if ($tahun)
            return $this->createdResponse($tahun);

        return $this->storeFailedResponse();

    }

    public function destroy($id)
    {
        $tahun = TahunRekening::findOrFail($id);

//        if ($tahun->rekening_akun->count() > 0)
//            return response()->json(['status' => false, 'message' => 'Gagal menghapus data tahun rekening.'], 400);

        $tahun->delete();
        return response()->json(['status' => true, 'message' => 'Berhasil menghapus data tahun rekening.'], 200);
    }

    public function activate($id)
    {
        $tahun = TahunRekening::findOrFail($id);
        $tahun->status = 1;
        $tahun->save();

        if ($tahun){
            $deactivateOthers = TahunRekening::where('id', '!=', $id)->update(['status' => 0]);
            return $this->successResponse(null, 'Berhasil mengaktifkan tahun rekening');
        }

        return response()->json(['status' => false, 'message' => 'Gagal mengaktifkan tahun rekening.'], 400);

    }
}
