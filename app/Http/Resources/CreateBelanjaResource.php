<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CreateBelanjaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'unit_id' => $this->unit_id,
            'nama_unit' => $this->unit->nama_unit,
            'jenis_id' => $this->jenis_id,
            'nama_jenis' => $this->jenis->nama_jenis,
            'uraian' => $this->uraian,
            'nilai' => $this->nilai,
            'nilai_pembiayaan' => $this->nilai_pembiayaan,
            'pembiayaan_id' => $this->pembiayaan_id,
//            'sumber_dana_pendapatan' => $this->kertas_kerja_pendapatan->jenis->nama_jenis
        ];
    }
}
