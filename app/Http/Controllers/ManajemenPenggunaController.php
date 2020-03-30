<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use DataTables;

class ManajemenPenggunaController extends Controller
{
    public function index()
    {
        return view('manajemen-pengguna');
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

    public function show($id)
    {

    }
}
