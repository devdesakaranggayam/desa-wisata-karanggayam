<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserStampList extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $gameStamp = $this->gameStamp;
        return [
            "id" => $gameStamp->id,
            "nama" => $gameStamp->nama,
            "icon_url" => $gameStamp->icon_url
        ];
    }
}
