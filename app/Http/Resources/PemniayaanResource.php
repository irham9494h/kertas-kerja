<?php

namespace App\Http\Resources;

use App\Models\KertasKerjaPendapatan;
use App\Models\KertasKerjaPembiayaan;
use Illuminate\Http\Resources\Json\JsonResource;

class PemniayaanResource extends JsonResource
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
            'list_uraian' => $this->uraianPembiayaan($this->sd_tanggal_id, $this->unit_id)
        ];
    }

    public function uraianPembiayaan($tgl_id, $unit_id)
    {
        $pembiayaan = KertasKerjaPembiayaan::with('jenis')
            ->where('unit_id', '=', $unit_id)
            ->where('sd_tanggal_id', '=', $tgl_id)
            ->get();

        return $pembiayaan;
//        return UraianPendapatanResource::collection($pendapatan);
    }
}
