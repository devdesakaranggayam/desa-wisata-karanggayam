<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\FileResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ProdukResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $file = map_files($this->files);
        return [
            "id" => $this->id,
            "nama" => $this->nama,
            "nama_toko" => $this->toko->nama ?? "-",
            "harga" => (float) ($this->harga ?? 0),
            "detail_url" => route('api.produk.show', $this->id),
            "thumbnail_url"=> $file[0]["file_url"] ?? default_img("produk")
        ];
    }
}
