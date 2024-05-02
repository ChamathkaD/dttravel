<?php

namespace App\Repositories;

use App\Interfaces\TravelPackageRepositoryInterface;
use App\Models\TravelCategory;
use App\Models\TravelDestination;
use App\Models\TravelPackage;
use App\Models\TravelPackageImage;
use App\Models\ValueAdd;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;

class TravelPackageRepository implements TravelPackageRepositoryInterface
{
    /**
     * Get all travel packages, optionally including soft-deleted ones.
     *
     * This method retrieves all travel packages from the database, allowing for the inclusion
     * of soft-deleted packages if the 'trashed' parameter is set in the request. For each travel
     * package, additional information such as category and destination names are attached.
     *
     * @return array|Collection An array or collection containing information about each travel package.
     */
    public function getAllTravelPackages(): array|Collection
    {
        if (request()->trashed) {
            $travel_packages = TravelPackage::onlyTrashed()
                ->latest()
                ->get();

            activity()
                ->log('Showed trashed travel packages');
        } else {
            $travel_packages = TravelPackage::latest()->get();

            activity()
                ->log('Showed all travel packages');
        }

        return $travel_packages->each(function ($travel_package) {
            return [
                $travel_package->category = TravelCategory::find($travel_package->travel_category_id)->name ?? null,
                $travel_package->destination = TravelDestination::find($travel_package->travel_destination_id)->name ?? null,
            ];
        });
    }

    /**
     * Store a new travel package with the provided data.
     *
     * @param  array  $data
     *      The data for creating the travel package. It should include details such as
     *      'meta_image', 'travel_package_images', 'itineraries', 'inclusions',
     *      'exclusions', 'valueAdds', and 'conditions'.
     *
     * @throws Exception
     *      Throws an exception if there is an error during the creation process.
     */
    public function storeTravelPackage(array $data): void
    {
        $travel_package = TravelPackage::create($data);

        $this->saveMetaImage($data, $travel_package);

        $this->saveTravelPackageImages($data, $travel_package);

        if (isset($data['itineraries'])) {
            foreach ($data['itineraries'] as $itinerary) {
                $travel_package->itineraries()->create([
                    'day' => $itinerary['day'],
                    'title' => $itinerary['title'],
                    'description' => $itinerary['description'],
                    'accommodation' => $itinerary['accommodation'],
                    'food' => $itinerary['food'],
                    'location' => $itinerary['location'],
                ]);
            }
        }

        if (isset($data['inclusions'])) {
            foreach ($data['inclusions'] as $inclusion) {
                $travel_package->inclusions()->attach($inclusion);
            }
        }

        if (isset($data['exclusions'])) {
            foreach ($data['exclusions'] as $exclusion) {
                $travel_package->exclusions()->attach($exclusion);
            }
        }

        if (isset($data['valueAdds'])) {
            foreach ($data['valueAdds'] as $valueAdd) {
                $travel_package->valueAdds()->attach($valueAdd);
            }
        }

        if (isset($data['conditions'])) {
            foreach ($data['conditions'] as $condition) {
                $travel_package->conditions()->attach($condition);
            }
        }

        activity()
            ->performedOn($travel_package)
            ->log('Created Travel Package');
    }

    /**
     * Update a travel package with the provided data.
     *
     * This method updates the attributes of a given travel package with the provided data.
     * It also handles updating or creating associated photos, itineraries, inclusions, exclusions,
     * value adds, and conditions based on the incoming data.
     *
     * @param  array  $data The data to update the travel package with.
     * @param  TravelPackage  $travelPackage The travel package to be updated.
     */
    public function updateTravelPackage(array $data, TravelPackage $travelPackage): void
    {
        $travelPackage->update($data);

        $this->saveMetaImage($data, $travelPackage);

        $this->saveTravelPackageImages($data, $travelPackage);

        if (isset($data['itineraries'])) {
            foreach ($data['itineraries'] as $itinerary) {
                $travelPackage->itineraries()->updateOrCreate(
                    [
                        'day' => $itinerary['day'],
                    ],
                    [
                        'day' => $itinerary['day'],
                        'title' => $itinerary['title'],
                        'description' => $itinerary['description'],
                        'accommodation' => $itinerary['accommodation'],
                        'food' => $itinerary['food'],
                        'location' => $itinerary['location'],
                    ]);
            }
        }

        if (isset($data['inclusions'])) {
            $inclusionIDs = $travelPackage->inclusions->pluck('pivot.inclusion_id');

            if (! empty($inclusionIDs)) {
                $travelPackage->inclusions()->detach($inclusionIDs);
            }

            foreach ($data['inclusions'] as $inclusion) {
                $travelPackage->inclusions()->attach($inclusion);
            }
        }

        if (isset($data['exclusions'])) {
            $exclusionIDs = $travelPackage->exclusions->pluck('pivot.exclusion_id');

            if (! empty($exclusionIDs)) {
                $travelPackage->exclusions()->detach($exclusionIDs);
            }

            foreach ($data['exclusions'] as $exclusion) {
                $travelPackage->exclusions()->attach($exclusion);
            }
        }

        if (isset($data['valueAdds'])) {
            $valueAddsIDs = $travelPackage->valueAdds->pluck('pivot.value_add_id');

            if (! empty($valueAddsIDs)) {
                $travelPackage->valueAdds()->detach($valueAddsIDs);
            }

            foreach ($data['valueAdds'] as $valueAdd) {
                $travelPackage->valueAdds()->attach($valueAdd);
            }
        }

        if (isset($data['conditions'])) {
            $conditionsIDs = $travelPackage->conditions->pluck('pivot.condition_id');

            if (! empty($conditionsIDs)) {
                $travelPackage->conditions()->detach($conditionsIDs);
            }

            foreach ($data['conditions'] as $condition) {
                $travelPackage->conditions()->attach($condition);
            }
        }

        activity()
            ->performedOn($travelPackage)
            ->log('Updated Travel Package');
    }

    /**
     * Delete a TravelPackage.
     *
     * @param  TravelPackage  $travelPackage The TravelPackage instance to be deleted.
     */
    public function deleteTravelPackage(TravelPackage $travelPackage): void
    {
        $travelPackage->delete();

        activity()
            ->performedOn($travelPackage)
            ->log('Deleted Travel Package into trash');
    }

    /**
     * Delete the meta image associated with a TravelPackage.
     *
     * This method is responsible for deleting the meta image associated with a given TravelPackage.
     * It checks if the TravelPackage has a valid meta image before initiating the deletion process.
     * The meta image is deleted using the deletePhoto method.
     *
     * @param  TravelPackage  $travelPackage The TravelPackage instance for which the meta image will be deleted.
     */
    public function deleteTravelPackageMetaImage(TravelPackage $travelPackage): void
    {
        if ($travelPackage->meta_image) {
            $travelPackage->deletePhoto($travelPackage->meta_image, 'meta_image');
        }

        activity()
            ->performedOn($travelPackage)
            ->log('Deleted Travel Package Meta Image');
    }

    /**
     * Restore a soft-deleted travel package by its ID.
     *
     * This method restores a travel package that has been soft-deleted. Soft deletion is a mechanism
     * where records are not permanently deleted from the database but are marked as "trashed"
     * or "soft-deleted." This function specifically restores a travel package by its unique identifier.
     *
     * @param  string  $travel_package_id The unique identifier of the travel package to be restored.
     */
    public function restoreTravelPackage(string $travel_package_id): void
    {
        TravelPackage::onlyTrashed()->where('id', $travel_package_id)->restore();

        $travelPackage = TravelPackage::find($travel_package_id);

        activity()
            ->performedOn($travelPackage)
            ->log('Restored Travel Package');
    }

    /**
     * Permanently delete a travel package along with related data.
     *
     * This method performs a force delete on a travel package, removing it permanently from the
     * database. It also takes care of additional cleanup tasks such as deleting the associated
     * meta image and travel package images.
     *
     * @param  string  $travel_package_id The unique identifier of the travel package to be permanently deleted.
     *
     * @throws ModelNotFoundException If the specified travel package is not found.
     */
    public function forceDeleteTravelPackage(string $travel_package_id): void
    {
        // Get Travel Package
        $travelPackage = TravelPackage::onlyTrashed()->findOrFail($travel_package_id);

        if ($travelPackage->meta_image) {
            $travelPackage->deletePhoto($travelPackage->meta_image, 'meta_image');
        }

        if ($travelPackage->travelPackageImages()->exists()) {
            foreach ($travelPackage->travelPackageImages as $travelPackageImage) {
                $travelPackageImage->deletePhoto($travelPackageImage->image_path, 'image_path');
            }
        }

        $travelPackage->forceDelete();

        activity()
            ->performedOn($travelPackage)
            ->withProperties(['user' => $travelPackage])
            ->log('Deleted Travel Package Permanently');
    }

    protected function saveMetaImage(array $data, TravelPackage $travel_package): void
    {
        if (isset($data['meta_image'])) {
            $travel_package->updatePhoto($data['meta_image'], 'meta_image', 'travel-package/meta');
        }
    }

    protected function saveTravelPackageImages(array $data, TravelPackage $travel_package): void
    {
        if (isset($data['travel_package_images'])) {
            foreach ($data['travel_package_images'] as $image) {
                $travel_package_image = new TravelPackageImage();
                $travel_package_image->travel_package_id = $travel_package->id;
                $travel_package_image->updatePhoto($image['file'], 'image_path', 'travel-package/images');
                $travel_package_image->save();
            }
        }
    }

    /**
     * Store value adds in the database and update the photo.
     */
    public function storeValueAdds(array $data): void
    {
        // Create a new value add record
        $value_add = ValueAdd::create($data);
        // Update the photo for the value add
        $value_add->updatePhoto($data['icon'], 'icon', 'travel-package/value-add/icons');
    }
}
