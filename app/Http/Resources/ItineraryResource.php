<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ItineraryResource extends JsonResource
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
            'day' => $this->day,
            'title' => $this->title,
            'description' => $this->description,
            'accommodation' => $this->accommodation,
            'food' => $this->food,
            'location' => $this->location,
        ];
    }
}
