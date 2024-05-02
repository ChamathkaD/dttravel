<?php

namespace App\Repositories;

use App\Interfaces\TravelPackageImageRepositoryInterface;
use App\Models\TravelPackageImage;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TravelPackageImageRepository implements TravelPackageImageRepositoryInterface
{
    /**
     * Delete a TravelPackageImage and its associated photo.
     *
     * This method is responsible for deleting a TravelPackageImage based on the provided identifier.
     * It checks if the TravelPackageImage exists and has a valid image path before initiating the deletion process.
     * The associated photo is deleted using the deletePhoto method, and then the TravelPackageImage record is deleted.
     *
     * @param  string  $id The unique identifier of the TravelPackageImage to be deleted.
     *
     * @throws ModelNotFoundException If the specified TravelPackageImage is not found.
     */
    public function deleteTravelPackages(string $id): void
    {
        $travel_package_image = TravelPackageImage::findOrFail($id);

        if ($travel_package_image && $travel_package_image->image_path) {
            $travel_package_image->deletePhoto($travel_package_image->image_path, 'image_path');
            $travel_package_image->delete();
        }

        activity()
            ->log('Deleted Travel Package Image');
    }
}
