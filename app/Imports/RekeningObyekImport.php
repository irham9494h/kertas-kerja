<?php

namespace App\Imports;

use App\Models\RekeningObyek;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;

class RekeningObyekImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new RekeningObyek([
            'id' => $row[0],
            'kode' => $row[1],
            'nama_obyek' => $row[2],
            'jenis_id' => $row[3],
            'created_by' => Auth::user()->id
        ]);
    }
}
