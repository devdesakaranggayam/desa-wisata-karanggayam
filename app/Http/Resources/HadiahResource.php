<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HadiahResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "nama" => $this->nama,
            "deskripsi" => $this->deskripsi,
            "min_stamp" => $this->min_stamp,
            "thumbnail_url" => $this->thumbnail->file_url,
        ];
    }
}
