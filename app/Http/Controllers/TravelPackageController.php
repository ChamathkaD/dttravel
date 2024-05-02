<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreConditionRequest;
use App\Http\Requests\StoreExclusionRequest;
use App\Http\Requests\StoreInclusionRequest;
use App\Http\Requests\StoreTravelPackageRequest;
use App\Http\Requests\StoreValueAddRequest;
use App\Http\Requests\UpdateTravelPackageRequest;
use App\Interfaces\TravelPackageRepositoryInterface;
use App\Models\Condition;
use App\Models\Exclusion;
use App\Models\Inclusion;
use App\Models\TravelCategory;
use App\Models\TravelDestination;
use App\Models\TravelPackage;
use App\Models\ValueAdd;

class TravelPackageController extends Controller
{
    private TravelPackageRepositoryInterface $travelPackageRepository;

    public function __construct(TravelPackageRepositoryInterface $travelPackageRepository)
    {
        $this->travelPackageRepository = $travelPackageRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return inertia('TravelPackage/ListTravelPackage', [
            'travelPackages' => $this->travelPackageRepository->getAllTravelPackages(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return inertia('TravelPackage/CreateTravelPackage', [
            'destinations' => TravelDestination::all(),
            'categories' => TravelCategory::all(),
            'inclusions' => Inclusion::all(),
            'exclusions' => Exclusion::all(),
            'value_adds' => ValueAdd::all(),
            'conditions' => Condition::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTravelPackageRequest $request)
    {
        $this->travelPackageRepository->storeTravelPackage($request->all());
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TravelPackage $travelPackage)
    {
        return inertia('TravelPackage/EditTravelPackage', [
            'travelPackage' => $travelPackage->load(['itineraries', 'travelPackageImages', 'conditions']),
            'inclusionIDs' => $travelPackage->inclusions->pluck('pivot.inclusion_id'),
            'exclusionIDs' => $travelPackage->exclusions->pluck('pivot.exclusion_id'),
            'valueAddsIDs' => $travelPackage->valueAdds->pluck('pivot.value_add_id'),
            'conditionIDs' => $travelPackage->conditions->pluck('pivot.condition_id'),
            'categories' => TravelCategory::all(),
            'destinations' => TravelDestination::all(),
            'inclusions' => Inclusion::all(),
            'exclusions' => Exclusion::all(),
            'value_adds' => ValueAdd::all(),
            'conditions' => Condition::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTravelPackageRequest $request, TravelPackage $travelPackage)
    {
        $this->travelPackageRepository->updateTravelPackage($request->all(), $travelPackage);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TravelPackage $travelPackage)
    {
        $this->travelPackageRepository->deleteTravelPackage($travelPackage);
    }

    public function storeInclusions(StoreInclusionRequest $request)
    {
        Inclusion::create($request->all());
    }

    public function deleteInclusion(Inclusion $inclusion)
    {
        $inclusion->delete();
    }

    public function storeExclusions(StoreExclusionRequest $request)
    {
        Exclusion::create($request->all());
    }

    public function deleteExclusion(Exclusion $exclusion)
    {
        $exclusion->delete();
    }

    public function storeValueAdds(StoreValueAddRequest $request)
    {
        $this->travelPackageRepository->storeValueAdds($request->all());
    }

    public function deleteValueAdds(ValueAdd $valueAdd)
    {
        if ($valueAdd->icon) {
            $valueAdd->deletePhoto($valueAdd->icon, 'icon');
        }

        $valueAdd->delete();
    }

    public function storeConditions(StoreConditionRequest $request)
    {
        Condition::create($request->all());
    }

    public function deleteCondition(Condition $condition)
    {
        $condition->delete();
    }

    public function deleteMetaImage(TravelPackage $travelPackage)
    {
        $this->travelPackageRepository->deleteTravelPackageMetaImage($travelPackage);
    }

    public function restore($travel_package_id = null)
    {
        $this->travelPackageRepository->restoreTravelPackage($travel_package_id);
    }

    public function fDelete($travel_package_id = null)
    {
        $this->travelPackageRepository->forceDeleteTravelPackage($travel_package_id);
    }
}
