<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTravelerContactDetailsRequest;
use App\Http\Requests\StoreTravelerHighlightsRequest;
use App\Http\Requests\StoreTravelerPersonalDetailsRequest;
use App\Http\Requests\UpdateTravelerContactDetailsRequest;
use App\Http\Requests\UpdateTravelerHighlightsRequest;
use App\Http\Requests\UpdateTravelerPersonalDetailsRequest;
use App\Interfaces\UserRepositoryInterface;
use App\Models\User;
use App\Services\AgeCalculationService;
use Illuminate\Support\Facades\DB;

class TravelerController extends Controller
{
    private UserRepositoryInterface $userRepository;

    protected AgeCalculationService $ageCalculationService;

    public function __construct(
        UserRepositoryInterface $userRepository,
        AgeCalculationService $ageCalculationService,
    ) {
        $this->userRepository = $userRepository;
        $this->ageCalculationService = $ageCalculationService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return inertia('Travelers/TravelerList')->with([
            'travelers' => $this->userRepository->getAllTravelerUsers(),
            'countries' => DB::table('countries')->select('name')->get(),
            'languages' => DB::table('languages')->select('value')->get(),
        ]);
    }

    public function validatePersonalForm(StoreTravelerPersonalDetailsRequest $request)
    {
    }

    public function validateContactForm(StoreTravelerContactDetailsRequest $request)
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTravelerHighlightsRequest $request)
    {
        User::withoutEvents(function () use ($request) {
            $request->mergeIfMissing(['role' => 'Traveler', 'agent_id' => auth()->id()]);
            $this->userRepository->createUser($request->all());
        });
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $traveler = User::role('Traveler')->findOrFail($id);

        return inertia('Travelers/TravelerDetails', [
            'traveler' => $traveler,
            'countries' => DB::table('countries')->select('name')->get(),
            'travelerAge' => $this->ageCalculationService->calculateAge($traveler->dob),
            'travelerRelations' => $this->userRepository->getTravelerRelationNameAndRelationship($traveler),
            'travelers' => $this->userRepository->getAllTravelerUsers(),
            'languages' => DB::table('languages')->select('value')->get(),
        ]);
    }

    public function updatePersonalDetails(UpdateTravelerPersonalDetailsRequest $request, User $user)
    {
        $this->userRepository->updateUser($request->validated(), $user);
    }

    public function updateContactDetails(UpdateTravelerContactDetailsRequest $request, User $user)
    {
        $this->userRepository->updateUser($request->validated(), $user);
    }

    public function updateHighlightDetails(UpdateTravelerHighlightsRequest $request, User $user)
    {
        $this->userRepository->updateUser($request->all(), $user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $traveler = User::role('Traveler')->findOrFail($id);

        $traveler->delete();

        if ($traveler->agent_id) {
            return to_route('travelers.index', ['withAgent' => true]);
        }

        return to_route('travelers.index');
    }
}
