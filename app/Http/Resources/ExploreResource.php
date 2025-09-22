<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExploreResource extends JsonResource
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

        return [
            "id" => $this->id,
            "nama" => $this->nama,
            "thumbnail_url" => $this->files()->first()->file_url ?? $thumbnail[$this->type],
            "tipe" => $this->type
        ];
    }
}
