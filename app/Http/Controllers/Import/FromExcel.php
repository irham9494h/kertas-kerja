<?php

namespace App\Http\Controllers\Import;

use App\Imports\RekeningJenisImport;
use App\Imports\RekeningObyekImport;
use App\Imports\RekeningRincianObyekImport;
use App\Models\RekeningJenis;
use App\Models\RekeningKelompok;
use App\Models\RekeningObyek;
use App\Models\RekeningRincianObyek;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use App\Http\Controllers\Controller;
use App\Imports\RekeningKelompokImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class FromExcel extends Controller
{
    public function index()
    {
        return view('import.index');
    }

    public function prosesImport(Request $request)
    {
        $this->validate($request, [
            'file' => 'required'
        ]);

        if ($request->jenis == 'rek_akun') {
            Session::flash('sukses', 'ini akun!');
        } else if ($request->jenis == 'rek_kelompok') {
            $data = Excel::toCollection(new RekeningKelompokImport(), $request->file('file'));
            $this->importRekeningKelompok($data);
            Session::flash('sukses', 'Data Siswa Berhasil Diimport!');
        } else if ($request->jenis == 'rek_jenis') {
            $data = Excel::toCollection(new RekeningJenisImport(), $request->file('file'));
            $this->importRekeningJenis($data);
            Session::flash('sukses', 'Data Siswa Berhasil Diimport!');
        } else if ($request->jenis == 'rek_obyek') {
            $data = Excel::toCollection(new RekeningObyekImport(), $request->file('file'));
            $this->importRekeningoObyek($data);
            Session::flash('sukses', 'Data Siswa Berhasil Diimport!');
        }else if(($request->jenis == 'rek_rincian_obyek'))
        {
            $data = Excel::toCollection(new RekeningRincianObyekImport(), $request->file('file'));
            $this->importRincianObyek($data);
            Session::flash('sukses', 'Data Siswa Berhasil Diimport!');
        }

        return redirect()->back();
    }

    public function importRekeningKelompok($data)
    {
        foreach ($data[0] as $key => $val) {
            if ($key != 0) {
                if ($val[0] != null)
                    RekeningKelompok::create([
                        'id' => $val[0],
                        'kode' => $val[1],
                        'nama_kelompok' => $val[2],
                        'akun_id' => $val[3],
                        'created_by' => Auth::user()->id
                    ]);
            }
        }
    }

    public function importRekeningJenis($data)
    {
        foreach ($data[0] as $key => $val) {
            if ($key != 0) {
                if ($val[0] != null)
                    RekeningJenis::create([
                        'id' => $val[0],
                        'kode' => $val[1],
                        'nama_jenis' => $val[2],
                        'kelompok_id' => $val[3],
                        'created_by' => Auth::user()->id
                    ]);
            }
        }
    }

    public function importRekeningoObyek($data)
    {
        foreach ($data[0] as $key => $val) {
            if ($key != 0) {
                if ($val[0] != null)
                    RekeningObyek::create([
                        'id' => $val[0],
                        'kode' => $val[1],
                        'nama_obyek' => $val[2],
                        'jenis_id' => $val[3],
                        'created_by' => Auth::user()->id
                    ]);
            }
        }
    }

    public function importRincianObyek($data){
        foreach ($data[0] as $key => $val) {
            if ($key != 0) {
                if ($val[0] != null)
                    RekeningRincianObyek::create([
                        'id' => $val[0],
                        'kode' => $val[1],
                        'nama_rincian_obyek' => $val[2],
                        'obyek_id' => $val[3],
                        'created_by' => Auth::user()->id
                    ]);
            }
        }
    }
}
