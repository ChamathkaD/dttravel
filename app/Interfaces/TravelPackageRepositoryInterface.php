<?php

namespace App\Interfaces;

use App\Models\TravelPackage;

interface TravelPackageRepositoryInterface
{
    public function getAllTravelPackages();

    public function storeTravelPackage(array $data);

    public function updateTravelPackage(array $data, TravelPackage $travelPackage);

    public function deleteTravelPackage(TravelPackage $travelPackage);

    public function deleteTravelPackageMetaImage(TravelPackage $travelPackage);

    public function restoreTravelPackage(string $travel_package_id);

    public function forceDeleteTravelPackage(string $travel_package_id);

    public function storeValueAdds(array $data);
}
