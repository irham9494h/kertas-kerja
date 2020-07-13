<?php

namespace App\Imports;

use App\Models\RekeningKelompok;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;

class RekeningKelompokImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new RekeningKelompok([
            'id' => $row[0],
            'kode' => $row[1],
            'nama_kelompok' => $row[2],
            'akun_id' => $row[3],
            'created_by' => Auth::user()->id
        ]);
    }

}
