<?php

namespace App\Http\Controllers;

use App\Models\OrganisasiBidang;
use App\Models\OrganisasiSubUnit;
use App\Models\OrganisasiUnit;
use App\Models\OrganisasiUrusan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrganisasiController extends AppController
{

    /*
     * Data Organisasi Urusan
     */
    public function index()
    {
        $urusans = OrganisasiUrusan::orderby('kode')->get();
        return view('organisasi', compact('urusans'));
    }

    public function storeUrusan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode_urusan' => 'required',
            'nama_urusan' => 'required',
        ], [
            'kode_urusan.required' => 'Kode urusan tidak boleh kosong.',
            'nama_urusan.required' => 'Nama urusan tidak boleh kosong.',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'error' => $validator->errors()]);
        }

        $urusan = OrganisasiUrusan::create([
            'kode' => $request->kode_urusan,
            'nama_urusan' => $request->nama_urusan,
            'created_by' => auth()->user()->id
        ]);

        if ($urusan)
            return $this->createdResponse($urusan);

        return $this->storeFailedResponse();
    }

    public function showUrusan($id)
    {
        $urusan = OrganisasiUrusan::findOrFail($id);
        if ($urusan)
            return $this->successResponse($urusan);

        return $this->nullResponse();
    }

    public function updateUrusan(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'kode_urusan' => 'required',
            'nama_urusan' => 'required',
        ], [
            'kode_urusan.required' => 'Kode urusan tidak boleh kosong.',
            'nama_urusan.required' => 'Nama urusan tidak boleh kosong.',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'error' => $validator->errors()]);
        }

        $urusan = OrganisasiUrusan::findOrFail($id)->update([
            'kode' => $request->kode_urusan,
            'nama_urusan' => $request->nama_urusan,
        ]);

        if ($urusan)
            return $this->successResponse($urusan);

        return $this->storeFailedResponse();
    }

    public function deleteUrusan($id)
    {
        $urusan = OrganisasiUrusan::findOrFail($id);
        $urusan->delete();

        return response()->json(['status' => true, 'message' => 'Berhasil.']);
    }

    /*
     * Data Organisasi Bidang
     */
    function getBidang()
    {
        $bidang = OrganisasiBidang::orderBy('kode')->get();
        return $this->successResponse($bidang);
    }

    public function storeBidang(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode_bidang' => 'required',
            'nama_bidang' => 'required',
        ], [
            'kode_bidang.required' => 'Kode bidang tidak boleh kosong.',
            'nama_bidang.required' => 'Nama bidang tidak boleh kosong.',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'error' => $validator->errors()]);
        }

        $bidang = OrganisasiBidang::create([
            'urusan_id' => $request->urusan_id,
            'kode' => $request->kode_bidang,
            'nama_bidang' => $request->nama_bidang,
            'created_by' => auth()->user()->id
        ]);

        if ($bidang)
            return $this->createdResponse($bidang);

        return $this->storeFailedResponse();
    }

    function getBidangByUrusan($urusan_id)
    {
        $bidang = OrganisasiBidang::with('urusan')->where('urusan_id', '=', $urusan_id)->orderBy('kode')->get();
        return $this->successResponse($bidang);
    }

    public function showBidang($id)
    {
        $urusan = OrganisasiBidang::with('urusan')->findOrFail($id);
        if ($urusan)
            return $this->successResponse($urusan);

        return $this->nullResponse();
    }

    public function updateBidang(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'kode_bidang' => 'required',
            'nama_bidang' => 'required',
        ], [
            'kode_bidang.required' => 'Kode bidang tidak boleh kosong.',
            'nama_bidang.required' => 'Nama bidang tidak boleh kosong.',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'error' => $validator->errors()]);
        }

        $bidang = OrganisasiBidang::findOrFail($id)->update([
            'kode' => $request->kode_bidang,
            'nama_bidang' => $request->nama_bidang,
        ]);

        if ($bidang)
            return $this->successResponse($bidang);

        return $this->storeFailedResponse();
    }

    public function deleteBidang($id)
    {
        $urusan = OrganisasiBidang::findOrFail($id);
        $urusan->delete();

        return response()->json(['status' => true, 'message' => 'Berhasil.']);
    }

    /*
     * Data Organisasi Unit
     */
    function getUnit()
    {
        $unit = OrganisasiUnit::orderBy('kode')->get();
        return $this->successResponse($unit);
    }

    public function storeUnit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode_unit' => 'required',
            'nama_unit' => 'required',
        ], [
            'kode_unit.required' => 'Kode unit tidak boleh kosong.',
            'nama_unit.required' => 'Nama unit tidak boleh kosong.',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'error' => $validator->errors()]);
        }

        $unit = OrganisasiUnit::create([
            'bidang_id' => $request->bidang_id,
            'kode' => $request->kode_unit,
            'nama_unit' => $request->nama_unit,
            'created_by' => auth()->user()->id
        ]);

        if ($unit)
            return $this->createdResponse($unit);

        return $this->storeFailedResponse();
    }

    function getUnitByBidang($bidang_id)
    {
        $unit = OrganisasiUnit::with('bidang.urusan')->where('bidang_id', '=', $bidang_id)->orderBy('kode')->get();
        return $this->successResponse($unit);
    }

    public function showUnit($id)
    {
        $unit = OrganisasiUnit::with('bidang.urusan')->findOrFail($id);
        if ($unit)
            return $this->successResponse($unit);

        return $this->nullResponse();
    }

    public function updateUnit(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'kode_unit' => 'required',
            'nama_unit' => 'required',
        ], [
            'kode_unit.required' => 'Kode unit tidak boleh kosong.',
            'nama_unit.required' => 'Nama unit tidak boleh kosong.',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'error' => $validator->errors()]);
        }

        $unit = OrganisasiUnit::findOrFail($id)->update([
            'kode' => $request->kode_unit,
            'nama_unit' => $request->nama_unit,
        ]);

        if ($unit)
            return $this->successResponse($unit);

        return $this->storeFailedResponse();
    }

    public function deleteUnit($id)
    {
        $unit = OrganisasiUnit::findOrFail($id);
        $unit->delete();

        return response()->json(['status' => true, 'message' => 'Berhasil.']);
    }

    /*
     * Data Organisasi Sub Unit
     */
    function getSubUnit()
    {
        $unit = OrganisasiSubUnit::orderBy('kode')->get();
        return $this->successResponse($unit);
    }

    public function storeSubUnit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode_sub_unit' => 'required',
            'nama_sub_unit' => 'required',
        ], [
            'kode_sub_unit.required' => 'Kode sub unit tidak boleh kosong.',
            'nama_sub_unit.required' => 'Nama sub unit tidak boleh kosong.',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'error' => $validator->errors()]);
        }

        $unit = OrganisasiSubUnit::create([
            'unit_id' => $request->unit_id,
            'kode' => $request->kode_sub_unit,
            'nama_sub_unit' => $request->nama_sub_unit,
            'created_by' => auth()->user()->id
        ]);

        if ($unit)
            return $this->createdResponse($unit);

        return $this->storeFailedResponse();
    }

    function getSubUnitByUnit($unit_id)
    {
        $unit = OrganisasiSubUnit::with(['unit.bidang.urusan'])->where('unit_id', '=', $unit_id)->orderBy('kode')->get();
        return $this->successResponse($unit);
    }

    public function showSubUnit($id)
    {
        $unit = OrganisasiSubUnit::findOrFail($id);
        if ($unit)
            return $this->successResponse($unit);

        return $this->nullResponse();
    }

    public function updateSubUnit(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'kode_sub_unit' => 'required',
            'nama_sub_unit' => 'required',
        ], [
            'kode_sub_unit.required' => 'Kode sub unit tidak boleh kosong.',
            'nama_sub_unit.required' => 'Nama sub unit tidak boleh kosong.',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'error' => $validator->errors()]);
        }

        $unit = OrganisasiSubUnit::findOrFail($id)->update([
            'kode' => $request->kode_sub_unit,
            'nama_sub_unit' => $request->nama_sub_unit,
        ]);

        if ($unit)
            return $this->successResponse($unit);

        return $this->storeFailedResponse();
    }

    public function deleteSubUnit($id)
    {
        $unit = OrganisasiSubUnit::findOrFail($id);
        $unit->delete();

        return response()->json(['status' => true, 'message' => 'Berhasil.']);
    }

}
