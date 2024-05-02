<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTravelDestinationRequest;
use App\Http\Requests\UpdateTravelDestinationRequest;
use App\Interfaces\TravelDestinationRepositoryInterface;
use App\Models\TravelDestination;

class TravelDestinationController extends Controller
{
    private TravelDestinationRepositoryInterface $travelDestinationRepository;

    public function __construct(
        TravelDestinationRepositoryInterface $travelDestinationRepository,
    ) {
        $this->travelDestinationRepository = $travelDestinationRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->trashed) {
            $destinations = TravelDestination::onlyTrashed()->get();

            activity()
                ->log('Showed trashed travel destinations');
        } else {
            $destinations = $this->travelDestinationRepository->getDestinations();

            activity()
                ->log('Showed travel destinations');
        }

        return inertia('TravelDestinations/TravelDestinationList', [
            'destinations' => $destinations,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTravelDestinationRequest $request)
    {
        $this->travelDestinationRepository->createDestination($request->all());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTravelDestinationRequest $request, TravelDestination $travelDestination)
    {
        $this->travelDestinationRepository->updateDestination($request->all(), $travelDestination);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TravelDestination $travelDestination)
    {
        $this->travelDestinationRepository->deleteDestination($travelDestination);
    }

    public function restore($destination_id = null)
    {
        $this->travelDestinationRepository->restoreDestination($destination_id);
    }

    public function fDelete($destination_id = null)
    {
        $this->travelDestinationRepository->forceDeleteDestination($destination_id);
    }
}
