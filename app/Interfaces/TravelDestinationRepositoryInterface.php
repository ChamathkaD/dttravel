<?php

namespace App\Interfaces;

use App\Models\TravelDestination;

interface TravelDestinationRepositoryInterface
{
    public function getDestinations();

    public function createDestination(array $data);

    public function updateDestination(array $data, TravelDestination $destination);

    public function deleteDestination(TravelDestination $destination);

    public function restoreDestination(string $destination_id);

    public function forceDeleteDestination(string $destination_id);
}
