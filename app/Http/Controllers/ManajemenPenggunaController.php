<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Validator;

class ManajemenPenggunaController extends Controller
{
    public function index()
    {
        return view('manajemen-pengguna');
    }

    public function fetchPengguna()
    {
        $pengguna = User::where('role', '!=', 'superadmin')->paginate(10);
        return response()->json(['data' => $pengguna], 200);
    }

    public function json()
    {
        return DataTables::of(User::get())
            ->addColumn('action', function ($data) {
                $button = '<button type="button" name="edit" id="' . $data->id . '" class="btn btn-warning btn-xs"><i class="fa fa-edit"></i></button>';
                $button .= '&nbsp;&nbsp;';
                $button .= '<button type="button" name="delete" id="' . $data->id . '" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></button>';
                return $button;
            })
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nip' => 'required',
            'nama' => 'required',
            'username' => 'required',
            'password' => 'required',
            'role' => 'required',
        ], [
            'nip.required' => 'NIP tidak boleh kosong.',
            'nama.required' => 'Nama pengguna tidak boleh kosong.',
            'username.required' => 'Username tidak boleh kosong.',
            'password.required' => 'Password tidak boleh kosong.',
            'role.required' => 'Anda belum memilih role pengguna.',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'error' => $validator->errors()]);
        }

        $user = User::create($request->all());
        if ($user)
            return $this->createdResponse($user);

        return $this->storeFailedResponse();
    }

    public function show($id)
    {

    }
}
