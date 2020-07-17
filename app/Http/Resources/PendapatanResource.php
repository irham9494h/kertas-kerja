<?php

namespace App\Http\Resources;

use App\Models\KertasKerjaPendapatan;
use Illuminate\Http\Resources\Json\JsonResource;

class PendapatanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'unit_id' => $this->unit_id,
            'nama_unit' => $this->unit->nama_unit,
            'rincian_obyek_id' => $this->rincian_obyek_id,
            'nama_rincian_obyek' => $this->rincian_obyek->nama_rincian_obyek,
            'uraian' => $this->uraian,
            'nilai' => $this->nilai,
            'list_uraian' => $this->uraianPendapatan($this->sd_tanggal_id, $this->unit_id)
        ];
    }

    public function uraianPendapatan($tgl_id, $unit_id)
    {
        $pendapatan = KertasKerjaPendapatan::with('rincian_obyek')
            ->where('unit_id', '=', $unit_id)
            ->where('sd_tanggal_id', '=', $tgl_id)
            ->get();

        return $pendapatan;
//        return UraianPendapatanResource::collection($pendapatan);
    }
}
