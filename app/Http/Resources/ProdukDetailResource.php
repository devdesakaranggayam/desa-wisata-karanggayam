<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProdukDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $files = map_files($this->files);
        return [
            "id" => $this->id,
            "nama" => $this->nama,
            "nama_toko" => $this->toko->nama ?? "-",
            "harga" => (float) ($this->harga ?? 0),
            "deskripsi" => $this->deskripsi,
            "thumbnail_url"=> $files[0]["file_url"] ?? default_img("produk"),
            "files" => $files,
            "lainnya" => $this->lainnya
        ];
    }
}
