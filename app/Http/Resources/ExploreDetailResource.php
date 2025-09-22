<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Resources\Json\JsonResource;

class ExploreDetailResource extends JsonResource
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
            "tipe" => $this->tipe,
            "deskripsi" => $this->deskripsi,
            "files" => map_files($this->files),
            "lainnya" => $this->lainnya
        ];
    }
}
