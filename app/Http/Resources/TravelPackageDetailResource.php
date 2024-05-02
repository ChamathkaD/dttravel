<?php

namespace App\Http\Resources;

use App\Helper\PriceManager;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TravelPackageDetailResource extends JsonResource
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
            'travelDestination' => $this->travelDestination?->name,
            'travelCategory' => $this->travelCategory?->name,
            'name' => $this->name,
            'description' => $this->description,
            'adultPrice' => ! empty($this->adults_price) ? PriceManager::format($this->adults_price) : '-',
            'adultPriceRaw' => $this->adults_price,
            'childPrice' => ! empty($this->child_price) ? PriceManager::format($this->child_price) : '-',
            'childPriceRaw' => $this->child_price,
            'discountedPrice' => ! empty($this->discounted_price) ? PriceManager::format($this->discounted_price) : '-',
            'discountedPriceRaw' => $this->discounted_price,
            'tax' => ! empty($this->tax) ? PriceManager::format($this->tax) : '-',
            'taxRaw' => $this->tax,
            'minPax' => $this->min_pax,
            'maxPax' => $this->max_pax,
            'isChargeTax' => $this->is_charge_tax,
            'isPublished' => $this->is_published,
            'metaTitle' => $this->meta_title,
            'metaDescription' => $this->meta_description,
            'metaKeyphrase' => $this->meta_keyphrase,
            'metaImage' => $this->metaImage,
            'isTopRated' => $this->bookings->count() > 2,
            'images' => $this->travelPackageImages,
            'inclusions' => InclusionResource::collection($this->inclusions),
            'exclusions' => ExclusionResource::collection($this->exclusions),
            'conditions' => ConditionResource::collection($this->conditions),
            'valueAddedServices' => ValueAddedServicesResource::collection($this->valueAdds),
            'itineraries' => ItineraryResource::collection($this->itineraries),
            'itineraryDays' => $this->itineraries->count(),
        ];
    }
}
