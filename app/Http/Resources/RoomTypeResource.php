<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoomTypeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
      return [
        'name'        => $this->name,
        'price'       => $this->price,
        'capacity'    => $this->capacity,
        'description' => $this->description,
        'services'     => $this->services->pluck('name')
        ];
    }
}
