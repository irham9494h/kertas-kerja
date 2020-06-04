<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UraianPendapatanResource extends JsonResource
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
        ];
    }
}
