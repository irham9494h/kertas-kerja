<?php

namespace App\Http\Controllers;

use App\Helper\RekeningHelper;
use App\Models\RekeningAkun;
use App\Models\RekeningJenis;
use App\Models\RekeningKelompok;
use App\Models\RekeningObyek;
use App\Models\RekeningRincianObyek;
use App\Models\TahunRekening;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RekeningController extends AppController
{
    /*
 * Data Rekening Akun
 */
    public function index()
    {
        $tahun = TahunRekening::where('status', '=', 1)->first();
        $akuns = RekeningAkun::where('tahun_rekening_id', '=', $tahun->id)->orderby('kode')->get();
        return view('rekening', compact('akuns', 'tahun'));
    }

    public function storeRekAkun(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode_akun' => 'required',
            'nama_akun' => 'required',
            'alias_akun' => 'required',
        ], [
            'kode_akun.required' => 'Kode akun tidak boleh kosong.',
            'nama_akun.required' => 'Nama akun tidak boleh kosong.',
            'alias_akun.required' => 'Anda belum memilih nama alias akun.',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'error' => $validator->errors()]);
        }

        $akun = RekeningAkun::create([
            'kode' => $request->kode_akun,
            'nama_akun' => $request->nama_akun,
            'alias' => $request->alias_akun,
            'tahun_rekening_id' => RekeningHelper::tahunRekening()->id,
            'created_by' => auth()->user()->id
        ]);

        if ($akun)
            return $this->createdResponse($akun);

        return $this->storeFailedResponse();
    }

    public function showRekAkun($id)
    {
        $akun = RekeningAkun::findOrFail($id);
        if ($akun)
            return $this->successResponse($akun);

        return $this->nullResponse();
    }

    public function updateRekAkun(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'kode_akun' => 'required',
            'nama_akun' => 'required',
            'alias_akun' => 'required',

        ], [
            'kode_akun.required' => 'Kode akun tidak boleh kosong.',
            'nama_akun.required' => 'Nama akun tidak boleh kosong.',
            'alias_akun.required' => 'Anda belum memilih nama alias akun.',

        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'error' => $validator->errors()]);
        }

        $akun = RekeningAkun::findOrFail($id)->update([
            'kode' => $request->kode_akun,
            'nama_akun' => $request->nama_akun,
            'alias' => $request->alias_akun,
        ]);

        if ($akun)
            return $this->successResponse($akun);

        return $this->storeFailedResponse();
    }

    public function deleteRekAkun($id)
    {
        $akun = RekeningAkun::findOrFail($id);
        $akun->delete();

        return response()->json(['status' => true, 'message' => 'Berhasil.']);
    }

    /*
     * Data Rekening Kelompok
     */
    function getKelompok()
    {
        $kelompok = RekeningKelompok::orderBy('kode')->get();
        return $this->successResponse($kelompok);
    }

    public function storeKelompok(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode_kelompok' => 'required',
            'nama_kelompok' => 'required',
        ], [
            'kode_kelompok.required' => 'Kode kelompok tidak boleh kosong.',
            'nama_kelompok.required' => 'Nama kelompok tidak boleh kosong.',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'error' => $validator->errors()]);
        }

        $kelompok = RekeningKelompok::create([
            'akun_id' => $request->akun_id,
            'kode' => $request->kode_kelompok,
            'nama_kelompok' => $request->nama_kelompok,
            'created_by' => auth()->user()->id
        ]);

        if ($kelompok)
            return $this->createdResponse($kelompok);

        return $this->storeFailedResponse();
    }

    function getKelompokByAkun($akun_id)
    {
        $kelompok = RekeningKelompok::with('akun')->where('akun_id', '=', $akun_id)->orderBy('kode')->get();
        return $this->successResponse($kelompok);
    }

    public function showKelompok($id)
    {
        $kelompok = RekeningKelompok::with('akun')->findOrFail($id);
        if ($kelompok)
            return $this->successResponse($kelompok);

        return $this->nullResponse();
    }

    public function updateKelompok(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'kode_kelompok' => 'required',
            'nama_kelompok' => 'required',
        ], [
            'kode_kelompok.required' => 'Kode kelompok tidak boleh kosong.',
            'nama_kelompok.required' => 'Nama kelompok tidak boleh kosong.',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'error' => $validator->errors()]);
        }

        $kelompok = RekeningKelompok::findOrFail($id)->update([
            'kode' => $request->kode_kelompok,
            'nama_kelompok' => $request->nama_kelompok,
        ]);

        if ($kelompok)
            return $this->successResponse($kelompok);

        return $this->storeFailedResponse();
    }

    public function deleteKelompok($id)
    {
        $kelompok = RekeningKelompok::findOrFail($id);
        $kelompok->delete();

        return response()->json(['status' => true, 'message' => 'Berhasil.']);
    }

    /*
    * Data Rekening Jenis
    */
    function getJenis()
    {
        $jenis = RekeningJenis::orderBy('kode')->get();
        return $this->successResponse($jenis);
    }

    public function storeJenis(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode_jenis' => 'required',
            'nama_jenis' => 'required',
        ], [
            'kode_jenis.required' => 'Kode jenis tidak boleh kosong.',
            'nama_jenis.required' => 'Nama jenis tidak boleh kosong.',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'error' => $validator->errors()]);
        }

        $jenis = RekeningJenis::create([
            'kelompok_id' => $request->kelompok_id,
            'kode' => $request->kode_jenis,
            'nama_jenis' => $request->nama_jenis,
            'created_by' => auth()->user()->id
        ]);

        if ($jenis)
            return $this->createdResponse($jenis);

        return $this->storeFailedResponse();
    }

    function getJenisByKelompok($kelompok_id)
    {
        $jenis = RekeningJenis::with('kelompok.akun')->where('kelompok_id', '=', $kelompok_id)->orderBy('kode')->get();
        return $this->successResponse($jenis);
    }

    public function showJenis($id)
    {
        $jenis = RekeningJenis::with('kelompok.akun')->findOrFail($id);
        if ($jenis)
            return $this->successResponse($jenis);

        return $this->nullResponse();
    }

    public function updateJenis(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'kode_jenis' => 'required',
            'nama_jenis' => 'required',
        ], [
            'kode_jenis.required' => 'Kode jenis tidak boleh kosong.',
            'nama_jenis.required' => 'Nama jenis tidak boleh kosong.',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'error' => $validator->errors()]);
        }

        $jenis = RekeningJenis::findOrFail($id)->update([
            'kode' => $request->kode_jenis,
            'nama_jenis' => $request->nama_jenis,
        ]);

        if ($jenis)
            return $this->successResponse($jenis);

        return $this->storeFailedResponse();
    }

    public function deleteJenis($id)
    {
        $jenis = RekeningJenis::findOrFail($id);
        $jenis->delete();

        return response()->json(['status' => true, 'message' => 'Berhasil.']);
    }

    /*
    * Data Rekening Objek
    */
    function getObjek()
    {
        $objek = RekeningObyek::orderBy('kode')->get();
        return $this->successResponse($objek);
    }

    public function storeObjek(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode_objek' => 'required',
            'nama_objek' => 'required',
        ], [
            'kode_objek.required' => 'Kode objek tidak boleh kosong.',
            'nama_objek.required' => 'Nama objek tidak boleh kosong.',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'error' => $validator->errors()]);
        }

        $objek = RekeningObyek::create([
            'jenis_id' => $request->jenis_id,
            'kode' => $request->kode_objek,
            'nama_obyek' => $request->nama_objek,
            'created_by' => auth()->user()->id
        ]);

        if ($objek)
            return $this->createdResponse($objek);

        return $this->storeFailedResponse();
    }

    function getObjekByJenis($jenis_id)
    {
        $objek = RekeningObyek::with('jenis.kelompok.akun')->where('jenis_id', '=', $jenis_id)->orderBy('kode')->get();
        return $this->successResponse($objek);
    }

    public function showObjek($id)
    {
        $objek = RekeningObyek::with('jenis.kelompok.akun')->findOrFail($id);
        if ($objek)
            return $this->successResponse($objek);

        return $this->nullResponse();
    }

    public function updateObjek(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'kode_objek' => 'required',
            'nama_objek' => 'required',
        ], [
            'kode_objek.required' => 'Kode objek tidak boleh kosong.',
            'nama_objek.required' => 'Nama objek tidak boleh kosong.',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'error' => $validator->errors()]);
        }

        $objek = RekeningObyek::findOrFail($id)->update([
            'kode' => $request->kode_objek,
            'nama_obyek' => $request->nama_objek,
        ]);

        if ($objek)
            return $this->successResponse($objek);

        return $this->storeFailedResponse();
    }

    public function deleteObjek($id)
    {
        $objek = RekeningObyek::findOrFail($id);
        $objek->delete();

        return response()->json(['status' => true, 'message' => 'Berhasil.']);
    }

    /*
    * Data Rekening Rincian Objek
    */
    function getRincianObjek()
    {
        $rincianObjek = RekeningRincianObyek::orderBy('kode')->get();
        return $this->successResponse($rincianObjek);
    }

    public function storeRincianObjek(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode_rincian_objek' => 'required',
            'nama_rincian_objek' => 'required',
        ], [
            'kode_rincian_objek.required' => 'Kode rincian objek tidak boleh kosong.',
            'nama_rincian_objek.required' => 'Nama rincian objek tidak boleh kosong.',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'error' => $validator->errors()]);
        }

        $rincianObjek = RekeningRincianObyek::create([
            'obyek_id' => $request->obyek_id,
            'kode' => $request->kode_rincian_objek,
            'nama_rincian_obyek' => $request->nama_rincian_objek,
            'created_by' => auth()->user()->id
        ]);

        if ($rincianObjek)
            return $this->createdResponse($rincianObjek);

        return $this->storeFailedResponse();
    }

    function getRincianObjekByObjek($obyek_id)
    {
        $objek = RekeningRincianObyek::with('obyek.jenis.kelompok.akun')->where('obyek_id', '=', $obyek_id)->orderBy('kode')->get();
        return $this->successResponse($objek);
    }

    public function showRincianObjek($id)
    {
        $rincianObjek = RekeningRincianObyek::with('obyek.jenis.kelompok.akun')->findOrFail($id);
        if ($rincianObjek)
            return $this->successResponse($rincianObjek);

        return $this->nullResponse();
    }

    public function updateRincianObjek(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'kode_rincian_objek' => 'required',
            'nama_rincian_objek' => 'required',
        ], [
            'kode_rincian_objek.required' => 'Kode rincian objek tidak boleh kosong.',
            'nama_rincian_objek.required' => 'Nama rincian objek tidak boleh kosong.',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'error' => $validator->errors()]);
        }

        $rincianObjek = RekeningRincianObyek::findOrFail($id)->update([
            'kode' => $request->kode_rincian_objek,
            'nama_rincian_obyek' => $request->nama_rincian_objek,
        ]);

        if ($rincianObjek)
            return $this->successResponse($rincianObjek);

        return $this->storeFailedResponse();
    }

    public function deleteRincianObjek($id)
    {
        $rincianObjek = RekeningRincianObyek::findOrFail($id);
        $rincianObjek->delete();

        return response()->json(['status' => true, 'message' => 'Berhasil.']);
    }
}
