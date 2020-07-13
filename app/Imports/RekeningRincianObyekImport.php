<?php

namespace App\Imports;

use App\Models\RekeningRincianObyek;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;

class RekeningRincianObyekImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new RekeningRincianObyek([
            'id' => $row[0],
            'kode' => $row[1],
            'nama_kelompok' => $row[2],
            'akun_id' => $row[3],
            'created_by' => Auth::user()->id
        ]);
    }
}
