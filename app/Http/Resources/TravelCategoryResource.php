<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TravelCategoryResource extends JsonResource
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
            'name' => $this->name,
            'slug' => $this->slug,
            'image' => $this->category_image_url,
            'description' => $this->description,
            'parent' => [
                'name' => $this->parent?->name,
                'slug' => $this->parent?->slug,
                'image' => $this->parent?->category_image_url,
            ],
        ];
    }
}
