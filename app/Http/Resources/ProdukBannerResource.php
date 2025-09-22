<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProdukBannerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "file_id" => $this->id,
            "produk_id" => $this->produk_id,
            "nama_produk" => $this->produk->nama ?? "-",
            "file_url" => $this->file_url ?? default_img("default"),
            "detail_url" => route('api.produk.show', $this->produk_id),
            "urutan" => $this->urutan
        ];
    }
}
