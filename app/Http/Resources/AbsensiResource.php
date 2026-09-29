<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AbsensiResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'         => $this->id,
            'tanggal'    => $this->tanggal,
            'jam_masuk'  => $this->jam_masuk,
            'jam_keluar' => $this->jam_keluar,
            'status'     => $this->status,
        ];
    }
}
