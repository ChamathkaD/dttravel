<?php

namespace App\Repositories;

use App\Interfaces\TravelDestinationRepositoryInterface;
use App\Models\TravelDestination;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Str;

class TravelDestinationRepository implements TravelDestinationRepositoryInterface
{
    /**
     * Retrieve all travel destinations with their descendants in descending order of creation.
     *
     * This method fetches all travel destinations along with their descendants using eager loading.
     * The result is ordered by the latest creation date, with the newest destinations appearing first.
     *
     * @return Collection|TravelDestination[]
     */
    public function getDestinations(): Collection|array
    {
        return TravelDestination::with('descendants')->latest()->get()->toTree();
    }

    /**
     * Create a new travel destination with the provided data.
     *
     * This method creates a new travel destination based on the given data array, including
     * the name, slug (generated from the name), and description. If a parent destination is
     * specified in the data, the new destination is appended as a child to that parent destination.
     *
     * Optionally, if a new destination image is provided, it is updated for the newly created destination.
     *
     * @param  array  $data An associative array containing data for creating the travel destination.
     *                    Expected keys: 'name', 'slug', 'description', 'parent_destination', 'photo'.
     */
    public function createDestination(array $data): void
    {
        $destination = TravelDestination::create([
            'name' => $data['name'],
            'slug' => Str::slug($data['slug']),
            'description' => $data['description'],
        ]);

        if (isset($data['parent_destination'])) {
            $parent = TravelDestination::find($data['parent_destination']);
            $destination->appendToNode($parent)->save();
        }

        // if a new destination image is specified, update it
        if (isset($data['photo'])) {
            $destination->updatePhoto($data['photo'], 'destination_image_path', 'destination-images');
        }

        activity()
            ->performedOn($destination)
            ->log('Created Travel Destination');
    }

    /**
     * Update the specified travel destination with the provided data.
     *
     * This method updates the attributes of the given travel destination based on the provided data array,
     * including the name, slug (generated from the name), and description. If a parent destination is
     * specified in the data, the destination is moved to become a child of that parent destination.
     *
     * Optionally, if a new destination image is provided, it is updated for the specified destination.
     *
     * @param  array  $data An associative array containing data for updating the travel destination.
     *                                   Expected keys: 'name', 'slug', 'description', 'parent_destination', 'photo'.
     * @param  TravelDestination  $destination The travel destination instance to be updated.
     */
    public function updateDestination(array $data, TravelDestination $destination): void
    {
        $destination->update([
            'name' => $data['name'],
            'slug' => Str::slug($data['slug']),
            'description' => $data['description'],
        ]);

        if (isset($data['parent_destination'])) {
            $parent = TravelDestination::find($data['parent_destination']);
            $parent->appendNode($destination);
        }

        // if a new destination image is specified, update it
        if (isset($data['photo'])) {
            $destination->updatePhoto($data['photo'], 'destination_image_path', 'destination-images');
        }

        activity()
            ->performedOn($destination)
            ->log('Updated Travel Destination');
    }

    /**
     * Delete the specified travel destination.
     *
     * @param  TravelDestination  $destination The travel destination instance to be deleted.
     */
    public function deleteDestination(TravelDestination $destination): void
    {
        $destination->delete();

        activity()
            ->performedOn($destination)
            ->log('Deleted travel destination into trash');
    }

    /**
     * Restore a soft-deleted travel destination by its ID.
     *
     * This method restores a travel destination that has been soft-deleted. Soft deletion is a mechanism
     * where records are not permanently deleted from the database but are marked as "trashed"
     * or "soft-deleted." This function specifically restores a travel destination by its unique identifier.
     *
     * @param  string  $destination_id The unique identifier of the travel destination to be restored.
     */
    public function restoreDestination(string $destination_id): void
    {
        TravelDestination::onlyTrashed()->where('id', $destination_id)->restore();

        $travel_destination = TravelDestination::find($destination_id);

        activity()
            ->performedOn($travel_destination)
            ->log('Restored Travel Destination');
    }

    /**
     * Permanently delete a travel destination along with related data.
     *
     * This method performs a force delete on a travel destination, removing it permanently from the
     * database. It also takes care of additional cleanup tasks such as deleting the associated destination image.
     *
     * @param  string  $destination_id The unique identifier of the travel destination to be permanently deleted.
     *
     * @throws ModelNotFoundException If the specified travel destination is not found.
     */
    public function forceDeleteDestination(string $destination_id): void
    {
        // Get Travel Destination
        $destination = TravelDestination::onlyTrashed()->findOrFail($destination_id);

        // Delete the photo
        if ($destination->destination_image_path) {
            $destination->deletePhoto($destination->destination_image_path, 'destination_image_path');
        }

        $destination->forceDelete();

        activity()
            ->performedOn($destination)
            ->withProperties(['destination' => $destination])
            ->log('Deleted Travel Destination Permanently');
    }
}
