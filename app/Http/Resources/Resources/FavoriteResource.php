<?php

namespace App\Http\Resources\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FavoriteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        //return parent::toArray($request);
        return[
            'anime-id' => $this->id,
            'title' => $this->title,
            'image' => $this->image,
            'created_at' => $this->created_at,
            'user_id' => $this->user_id,

        ];
        

    }
}
