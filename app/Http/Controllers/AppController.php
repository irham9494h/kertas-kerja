<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AppController extends Controller
{
    public function createdResponse($data, $totalSumberDana = null)
    {
        if ($totalSumberDana != null)
            return response()->json(['status' => true, 'message' => 'Berhasil menyimpan data.', 'data' => $data, 'totalSumberDana' => $totalSumberDana], 201);
        return response()->json(['status' => true, 'message' => 'Berhasil menyimpan data.', 'data' => $data], 201);
    }

    public function storeFailedResponse($message = null)
    {
        if ($message != null || $message != '')
            return response()->json(['status' => false, 'message' => $message], 400);
        return response()->json(['status' => false, 'message' => 'Gagal menyimpan data.'], 400);
    }

    public function dateInvalid()
    {
        return response()->json(['status' => false, 'message' => 'Gagal menambahkan tanggal kertas kerja dibawah tanggal yang sudah ada.'], 400);
    }

    public function successResponse($data, $totalSumberDana = null, $message = null)
    {
        if ($message != null)
            return response()->json(['status' => true, 'message' => 'Berhasil.', 'totalSumberDana' => $totalSumberDana, 'data' => $data], 200);
        return response()->json(['status' => true, 'message' => $message, 'totalSumberDana' => $totalSumberDana, 'data' => $data], 200);
    }

    public function nullResponse()
    {
        return response()->json(['status' => true, 'message' => 'Data kosong.'], 200);
    }
}
