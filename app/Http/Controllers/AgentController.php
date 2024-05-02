<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAgentRequest;
use App\Http\Requests\UpdateAgentAccountRequest;
use App\Http\Requests\UpdateAgentBusinessRequest;
use App\Interfaces\UserRepositoryInterface;
use App\Models\AgentPayment;
use App\Models\Booking;
use App\Models\User;
use App\Services\UserAccountValidationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AgentController extends Controller
{
    private UserRepositoryInterface $userRepository;

    private UserAccountValidationService $validationService;

    public function __construct(
        UserRepositoryInterface $userRepository,
        UserAccountValidationService $validationService,
    ) {
        $this->userRepository = $userRepository;
        $this->validationService = $validationService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return inertia('Agents/AgentList')->with([
            'agents' => $this->userRepository->getAllTravelAgentUsers(),
            'countries' => DB::table('countries')->select('name')->get(),
        ]);
    }

    public function stepOneCreate(Request $request)
    {
        $this->validationService->validateStepOne($request);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        User::withoutEvents(function () use ($request) {
            $this->validationService->validateStepTwo($request);

            $request->merge(['isMustChangePassword' => true]);

            $user = $this->userRepository->createUser($request->all());

            $this->userRepository->sendInvitationMailToAgent($user);
        });
    }

    public function inviteAgent(StoreAgentRequest $request)
    {
        $request->mergeIfMissing(['role' => 'Travel Agent']);

        $this->userRepository->createUser($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $agent = User::role('Travel Agent')->findOrFail($id);

        return inertia('Agents/AgentDetails', [
            'agent' => $agent,
            'countries' => DB::table('countries')->select('name')->get(),
            'totalAgentBookingCount' => $this->userRepository->getTotalAgentBooking($agent),
            'agentBookings' => $this->userRepository->getAgentBookingsWithTraveler($agent),
            'agentPayments' => $this->userRepository->getAgentPayments($agent),
            'totalSales' => $this->userRepository->getTotalSales($agent),
            'totalIncome' => $this->userRepository->getTotalIncome($agent),
        ]);
    }

    public function updateAccount(UpdateAgentAccountRequest $request, User $user)
    {
        $this->userRepository->updateUser($request->validated(), $user);
    }

    public function updateBusiness(UpdateAgentBusinessRequest $request, User $user)
    {
        $this->userRepository->updateUser($request->validated(), $user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id = null)
    {
        $agent = User::role('Travel Agent')->findOrFail($id);

        $this->userRepository->deleteUser($agent);

        return to_route('agents.index');
    }

    public function paymentHistory()
    {
        return inertia('Agents/Payment/PaymentHistory', [
            'totalBookingCount' => $this->userRepository->getTotalAgentBooking(auth()->user()),
            'totalSales' => $this->userRepository->getTotalSales(auth()->user()),
            'totalIncome' => $this->userRepository->getTotalIncome(auth()->user()),
            'agentPayments' => $this->userRepository->getAgentPayments(auth()->user()),
        ]);
    }

    public function paymentDetails(AgentPayment $agentPayment)
    {
        $booking = Booking::find($agentPayment->booking_id);

        $agent = User::find($booking->agent_id);

        $priceData = [
            'adultTotal' => floatval(str_replace(',', '', $booking->price_per_adult)) * $booking->adult_count * ($agent->agent_commission_rate / 100),
            'childTotal' => floatval(str_replace(',', '', $booking->price_per_child)) * $booking->child_count * ($agent->agent_commission_rate / 100),
        ];

        return inertia('Agents/Payment/PaymentDetails', [
            'agentPayment' => $agentPayment,
            'booking' => $booking,
            'agent' => $agent,
            'priceData' => $priceData,
        ]);
    }

    public function updateStatus(AgentPayment $agentPayment)
    {
        $agentPayment->status = 'paid';
        $agentPayment->save();

        activity()
            ->performedOn($agentPayment)
            ->withProperties(['user' => auth()->user()])
            ->log('Agent payment status updated as paid');
    }

    public function showInvoice(AgentPayment $agentPayment)
    {
        $booking = Booking::find($agentPayment->booking_id);

        $agent = User::find($booking->agent_id);

        $priceData = [
            'adultTotal' => floatval(str_replace(',', '', $booking->price_per_adult)) * $booking->adult_count * ($agent->agent_commission_rate / 100),
            'childTotal' => floatval(str_replace(',', '', $booking->price_per_child)) * $booking->child_count * ($agent->agent_commission_rate / 100),
        ];

        return inertia('Agents/Invoice', [
            'agentPayment' => $agentPayment,
            'booking' => $booking,
            'agent' => $agent,
            'priceData' => $priceData,
        ]);
    }
}
