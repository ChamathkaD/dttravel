<?php

namespace App\Http\Resources;

use App\Helper\PriceManager;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TravelPackageResource extends JsonResource
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
            'subDestinations' => $this->travelDestination?->descendants ?? [],
            'travelDestination' => $this->travelDestination?->name,
            'travelCategory' => $this->travelCategory?->name,
            'name' => $this->name,
            'description' => $this->description,
            'adultPrice' => ! empty($this->adults_price) ? PriceManager::format($this->adults_price) : '-',
            'childPrice' => ! empty($this->child_price) ? PriceManager::format($this->child_price) : '-',
            'discountedPrice' => ! empty($this->discounted_price) ? PriceManager::format($this->discounted_price) : '-',
            'tax' => ! empty($this->tax) ? PriceManager::format($this->tax) : '-',
            'minPax' => $this->min_pax,
            'maxPax' => $this->max_pax,
            'isChargeTax' => $this->is_charge_tax,
            'metaTitle' => $this->meta_title,
            'metaDescription' => $this->meta_description,
            'metaKeyphrase' => $this->meta_keyphrase,
            'metaImage' => $this->metaImage,
            'isTopRated' => $this->top_rated,
            'images' => $this->travelPackageImages,
        ];
    }
}
