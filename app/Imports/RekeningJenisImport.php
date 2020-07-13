<?php

namespace App\Imports;

use App\Models\RekeningJenis;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;

class RekeningJenisImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new RekeningJenis([
            'id' => $row[0],
            'kode' => $row[1],
            'nama_jenis' => $row[2],
            'kelompok_id' => $row[3],
            'created_by' => Auth::user()->id
        ]);
    }
}
