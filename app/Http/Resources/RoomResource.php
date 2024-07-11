<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoomResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'Room Code' => $this->code,
            'Room Type' => new RoomTypeResource($this->roomType),
            'Available Services'=>ServiceResource::collection($this->roomType->services),
            'floor Number' => $this->floorNumber,
            'description' => $this->description,
            'status' => $this->status,
            'price' => $this->price,
            'images paths' => array_map(function ($imgPath) {
                                return asset('images/'.$imgPath);
                                },json_decode($this->images,true)),
            'created_at' => $this->created_at->format('Y-M-d'),
            'updated_at' => $this->updated_at->format('Y-M-d'),
        ];
    }
}
