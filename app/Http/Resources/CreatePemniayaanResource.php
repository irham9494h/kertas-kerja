<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CreatePemniayaanResource extends JsonResource
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
            'rincian_obyek_id' => $this->rincian_obyek_id,
            'nama_rincian_obyek' => $this->rincian_obyek->nama_rincian_obyek,
            'uraian' => $this->uraian,
            'nilai' => $this->nilai
        ];
    }
}
