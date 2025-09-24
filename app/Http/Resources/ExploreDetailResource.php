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
        $thumbnail = [
            "kesenian" => default_img("kesenian"), 
            "wisata" => default_img("wisata"), 
        ];
        $files = map_files($this->files);

        return [
            "id" => $this->id,
            "nama" => $this->nama,
            "tipe" => $this->tipe,
            "deskripsi" => $this->deskripsi,
            "thumbnail_url"=> $files[0]["file_url"] ?? $thumbnail[$this->tipe],
            "files" => $files,
            "lainnya" => $this->lainnya
        ];
    }
}
