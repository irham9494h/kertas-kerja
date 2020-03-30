<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AppController extends Controller
{
    public function createdResponse($data)
    {
        return response()->json(['status' => true, 'message' => 'Berhasil menyimpan data.', 'data' => $data], 201);
    }

    public function storeFailedResponse()
    {
        return response()->json(['status' => false, 'message' => 'Gagal menyimpan data.'], 400);
    }

    public function successResponse($data)
    {
        return response()->json(['status' => true, 'message' => 'Berhasil.', 'data' => $data], 200);
    }

    public function nullResponse()
    {
        return response()->json(['status' => true, 'message' => 'Data kosong.'], 200);
    }
}
