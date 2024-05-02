<?php

namespace App\Http\Controllers;

use App\Interfaces\BookingRepositoryInterface;
use App\Interfaces\UserRepositoryInterface;
use App\Jobs\SendBookingNoticeEmail;
use App\Jobs\SendPaymentInvitationEmail;
use App\Models\AgentPayment;
use App\Models\Booking;
use App\Models\BookingTraveler;
use App\Models\Payment;
use App\Models\TravelPackage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class BookingController extends Controller
{
    private BookingRepositoryInterface $bookingRepository;

    private UserRepositoryInterface $userRepository;

    public function __construct(
        BookingRepositoryInterface $bookingRepository,
        UserRepositoryInterface $userRepository
    ) {
        $this->bookingRepository = $bookingRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->status == 'completed') {
            $bookings = $this->bookingRepository->getCompletedBookings();
        } elseif (request()->status == 'cancel') {
            $bookings = $this->bookingRepository->getCancelBookings();
        } else {
            $bookings = $this->bookingRepository->getUpcomingBookings();
        }

        return inertia('Bookings/BookingList')->with(compact('bookings'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        if (empty($user)) {
            return response()->json([
                'error' => true,
                'message' => 'User not logged in',
            ], 401);
        }

        $role = null;
        $agentId = null;
        $travelerId = null;
        if ($user->hasRole('Travel Agent')) {
            $role = 'Travel Agent';
            $agentId = Auth::id();
        } elseif ($user->hasRole('Traveler')) {
            $role = 'Traveler';
            $travelerId = Auth::id();
        }

        if (empty($role)) {
            return response()->json([
                'error' => true,
                'message' => 'User role must be Traveler or Travel Agent',
            ], 400);
        }

        $package = TravelPackage::find($request->package_id);
        if (empty($package)) {
            return response()->json([
                'error' => true,
                'message' => 'Invalid package',
            ], 400);
        }

        $adultCount = $request->adult_count;
        $childCount = $request->child_count;

        $price_per_adult = $package->adults_price;
        $price_per_child = $package->child_price;

        $total_adults_price = $price_per_adult * $adultCount;
        $total_child_price = $price_per_child * $childCount;

        $adult_discount = ($price_per_adult / 100 * $package->discounted_price) * $adultCount;
        $child_discount = ($price_per_child / 100 * $package->discounted_price) * $childCount;

        $adult_tax = ($price_per_adult / 100 * $package->tax) * $adultCount;
        $child_tax = ($price_per_child / 100 * $package->tax) * $childCount;

        $adultNetPrice = $total_adults_price - $adult_discount - $adult_tax;
        $childNetPrice = $total_child_price - $child_discount - $child_tax;

        $total_price = $total_adults_price + $total_child_price;

        $booking = new Booking();
        $booking->agent_id = $agentId;
        $booking->traveler_id = $travelerId;
        $booking->travel_package_id = $package->id;
        $booking->travel_date = $request->travel_date;
        $booking->adult_count = $adultCount;
        $booking->child_count = $childCount;
        $booking->price_per_adult = $price_per_adult;
        $booking->price_per_child = $price_per_child;
        $booking->total_adults_price = $total_adults_price;
        $booking->total_child_price = $total_child_price;
        $booking->adult_discount = $adult_discount;
        $booking->child_discount = $child_discount;
        $booking->adult_tax = $adult_tax;
        $booking->child_tax = $child_tax;
        $booking->adult_net_price = $adultNetPrice;
        $booking->child_net_price = $childNetPrice;

        $booking->amount = $total_price;
        $booking->total_tax = $adult_tax + $child_tax;
        $booking->total_discount = $adult_discount + $child_discount;
        $booking->net_amount = $total_price + ($adult_tax + $child_tax) - ($adult_discount + $child_discount);
        $booking->total_price = $total_price + ($adult_tax + $child_tax) - ($adult_discount + $child_discount);
        $booking->order_no = $this->generateOrderNumber();
        $booking->save();

        // if agent exist then send email to agent and process the agent payment
        if ($booking->agent_id) {
            $agent = User::find($booking->agent_id);

            if (empty($agent)) {
                return response()->json([
                    'error' => true,
                    'message' => 'Travel Agent Not Found',
                ], 404);
            }

            // Retrieve the agent's email
            $email = $agent->email;

            // Check if the agent's email is available
            if (empty($email)) {
                return response()->json([
                    'error' => true,
                    'message' => 'Agent Email Not Found',
                ], 404);
            }

            // Create agent payment record
            $agent_commission_rate = $agent->agent_commission_rate;
            $netAmountStringWithoutCommas = str_replace(',', '', $booking->net_amount);
            $net_amount = (float) $netAmountStringWithoutCommas;
            $b2b_amount = $net_amount * ($agent_commission_rate / 100);

            // Create agent payment record
            $agent_payment = new AgentPayment();
            $agent_payment->booking_id = $booking->id;
            $agent_payment->agent_id = $booking->agent_id;
            $agent_payment->total_sale = $net_amount;
            $agent_payment->billing_date = now();
            $agent_payment->b2b_amount = $b2b_amount;
            $agent_payment->save();

            // Dispatch email job
            SendBookingNoticeEmail::dispatch($email, $booking);
        }

        return response()->json([
            'success' => true,
            'message' => 'Booking created!',
            'data' => $booking,
        ], 200);

    }

    private function generateOrderNumber(): string
    {
        // Get the last order number and increment by one
        $lastOrder = Booking::orderBy('id', 'desc')->first();
        $lastOrderNumber = $lastOrder ? (int) substr($lastOrder->order_no, -6) : 0;

        return str_pad($lastOrderNumber + 1, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Display the specified resource.
     */
    public function show(Booking $booking)
    {
        $category = $booking->package->travelCategory;
        $destination = $booking->package->travelDestination;
        $maleCount = $booking->bookingTravelers->where('gender', 'male')->count();
        $feMaleCount = $booking->bookingTravelers->where('gender', 'feMale')->count();
        $agent = $booking->agent;
        $booking = $booking->load(['package', 'bookingTravelers', 'bookingNotes']);
        $travelers = $this->userRepository->getAllTravelerUsers();

        return inertia('Bookings/BookingDetails', compact('booking', 'category', 'destination', 'maleCount', 'feMaleCount', 'agent', 'travelers'));
    }

    public function showInvoice(Booking $booking)
    {
        return inertia('Bookings/Invoice/BookingInvoice', [
            'booking' => $booking->load(['package', 'bookingTravelers']),
            'agent' => User::role('Travel Agent')->find($booking->agent_id),
            'traveler' => User::role('Traveler')->find($booking->traveler_id),
        ]);
    }

    public function inviteBookingPayment(Request $request, Booking $booking)
    {
        $request->validate([
            'total_price' => 'required|numeric|min:1',
        ]);

        $this->bookingRepository->updateBookingPayment($request->only('total_price'), $booking);

        SendPaymentInvitationEmail::dispatch($booking);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Booking $booking)
    {
        $booking->delete();

        return to_route('bookings.index', ['status' => 'cancel']);
    }

    public function getBookingById(Request $request, $id)
    {
        $booking = Booking::with(['package', 'package.travelPackageImages', 'bookingTravelers'])->where('id', $id)->first();

        if ($booking->agent_id) {
            $user = User::find($booking->agent_id);
        } else {
            $user = User::find($booking->traveler_id);
        }
        $booking->user = $user;

        if ($booking->net_amount != 0) {
            $booking['webxpay_payment'] = $this->generateEncryptedValue($booking);
            $booking['custom_fields'] = base64_encode('cus_1|cus_2|cus_3|cus_4');
            $booking['webxpay_secret_key'] = env('WEBXPAY_SECRET_KEY');
        }

        return response()->json([
            'success' => true,
            'message' => 'Booking Found !',
            'data' => $booking,
        ], 200);
    }

    public function generateEncryptedValue($booking)
    {
        $netAmountStringWithoutCommas = str_replace(',', '', $booking->net_amount);
        $net_amount = (float) $netAmountStringWithoutCommas;
        $plaintext = sprintf('%s|%s', $booking->order_no, $net_amount);
        $publickey = env('WEBXPAY_PUBLIC_KEY');

        openssl_public_encrypt($plaintext, $encrypt, $publickey);

        return base64_encode($encrypt);
    }

    public function getBookingByOrderId(Request $request, $id)
    {
        $booking = Booking::with(['package', 'package.travelPackageImages', 'bookingTravelers'])->where('order_no', $id)->first();
        if ($booking instanceof Booking) {
            return response()->json([
                'success' => true,
                'message' => 'Booking Found !',
                'data' => $booking,
            ], 200);
        } else {
            return response()->json([
                'error' => true,
                'message' => 'No booking found for '.$id,
                'data' => [],
            ], 200);
        }

    }

    public function updateTravelers(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'travelers.*.firstName' => ['required', 'string', 'max:50', 'min:2'],
            'travelers.*.lastName' => ['required', 'string', 'max:50', 'min:2'],
            'travelers.*.callName' => ['required', 'string', 'max:50', 'min:2'],
            'travelers.*.dateOfBirth' => ['required', 'date'],
            'travelers.*.gender' => ['required', 'string'],
            'travelers.*.passportId' => ['required', 'string'],
            'travelers.*.dateOfDelivery' => ['required', 'date'],
            'travelers.*.dateOfExpire' => ['required', 'date'],
            'travelers.*.language' => ['required', 'string'],
            'travelers.*.travelerImg' => ['nullable', 'mimes:jpg,jpeg,png', 'max:1024'],
            'travelers.*.mobileNumber' => ['required', 'string', 'max:20', 'phone:INTERNATIONAL'],
            'travelers.*.whatsappNumber' => ['required', 'string', 'max:20', 'phone:INTERNATIONAL'],
            'travelers.*.emailAddress' => ['required', 'string', 'email', 'max:100', Rule::unique('users', 'email')],
            'travelers.*.address' => ['required', 'string'],
            'travelers.*.country' => ['required', 'string'],
            'travelers.*.state' => ['required', 'string'],
            'travelers.*.zipCode' => ['required', 'string'],
            'travelers.*.instagramUserName' => ['nullable', 'string'],
            'travelers.*.tiktokUserName' => ['nullable', 'string'],
            'travelers.*.facebookUserName' => ['nullable', 'string'],
            'travelers.*.emergencyContact1' => ['required', 'string', 'max:20', 'phone:INTERNATIONAL'],
            'travelers.*.emergencyContact2' => ['required', 'string', 'max:20', 'phone:INTERNATIONAL'],
            'travelers.*.relationship_traveler' => ['nullable'],
            'travelers.*.relationship' => ['nullable'],
            'travelers.*.drugStatus' => ['nullable', 'string'],
            'travelers.*.foodStatus' => ['nullable', 'string'],
            'travelers.*.diet' => ['nullable', 'string', 'max:250', 'min:10'],
            'travelers.*.meditation' => ['nullable', 'string', 'max:250', 'min:10'],
            'travelers.*.particular' => ['nullable', 'string', 'max:250', 'min:10'],
            'travelers.*.note' => ['nullable', 'string', 'max:250', 'min:10'],
            'travelers.*.flightNumber' => ['nullable', 'string', 'max:250', 'min:5'],
            'travelers.*.flightDate' => ['nullable', 'date'],
            'travelers.*.dispatcherTime' => ['nullable', 'date_format:H:i'],
            'travelers.*.dispatcherAirport' => ['nullable', 'string', 'max:250', 'min:5'],
            'travelers.*.arrivalTime' => ['nullable', 'date_format:H:i'],
            'travelers.*.arrivalAirport' => ['nullable', 'string', 'max:250', 'min:5'],
        ], [], [
            'travelers.*.firstName' => 'first name',
            'travelers.*.lastName' => 'last name',
            'travelers.*.callName' => 'call name',
            'travelers.*.dateOfBirth' => 'date of birth',
            'travelers.*.gender' => 'gender',
            'travelers.*.passportId' => 'passport id',
            'travelers.*.dateOfDelivery' => 'date of delivery',
            'travelers.*.dateOfExpire' => 'date of expire',
            'travelers.*.language' => 'language',
            'travelers.*.travelerImg' => 'traveler image',
            'travelers.*.mobileNumber' => 'mobile number',
            'travelers.*.whatsappNumber' => 'whatsapp number',
            'travelers.*.emailAddress' => 'email address',
            'travelers.*.address' => 'address',
            'travelers.*.country' => 'country',
            'travelers.*.state' => 'state',
            'travelers.*.zipCode' => 'zip code',
            'travelers.*.instagramUserName' => 'instagram user name',
            'travelers.*.tiktokUserName' => 'tiktok user name',
            'travelers.*.facebookUserName' => 'facebook user name',
            'travelers.*.emergencyContact1' => 'emergency contact 1',
            'travelers.*.emergencyContact2' => 'emergency contact 2',
            'travelers.*.relationship_traveler' => 'relationship',
            'travelers.*.relationship' => 'relationship',
            'travelers.*.drugStatus' => 'drug status',
            'travelers.*.foodStatus' => 'food status',
            'travelers.*.diet' => 'diet',
            'travelers.*.meditation' => 'meditation',
            'travelers.*.particular' => 'particular',
            'travelers.*.note' => 'note',
            'travelers.*.flightNumber' => 'flight number',
            'travelers.*.flightDate' => 'flight date',
            'travelers.*.dispatcherTime' => 'departure time',
            'travelers.*.dispatcherAirport' => 'departure airport',
            'travelers.*.arrivalTime' => 'arrival time',
            'travelers.*.arrivalAirport' => 'arrival airport',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $booking = Booking::findOrFail($id);
        $travelers = $request->travelers;

        foreach ($travelers as $traveler) {
            $bookingTraveler = new BookingTraveler();
            $bookingTraveler->booking_id = $booking->id;
            $bookingTraveler->created_by = Auth::id();
            $bookingTraveler->first_name = $traveler['firstName'];
            $bookingTraveler->last_name = $traveler['lastName'];
            $bookingTraveler->call_name = $traveler['callName'];
            $bookingTraveler->date_of_birth = $traveler['dateOfBirth'];
            $bookingTraveler->gender = $traveler['gender'];
            $bookingTraveler->passport_id = $traveler['passportId'];
            $bookingTraveler->date_of_delivery = $traveler['dateOfDelivery'];
            $bookingTraveler->date_of_expire = $traveler['dateOfExpire'];
            $bookingTraveler->language = $traveler['language'];
            $bookingTraveler->traveler_img = $traveler['travelerImg'];
            $bookingTraveler->mobile_number = $traveler['mobileNumber'];
            $bookingTraveler->whatsapp_number = $traveler['whatsappNumber'];
            $bookingTraveler->email_address = $traveler['emailAddress'];
            $bookingTraveler->address = $traveler['address'];
            $bookingTraveler->country = $traveler['country'];
            $bookingTraveler->state = $traveler['state'];
            $bookingTraveler->zip_code = $traveler['zipCode'];
            $bookingTraveler->instagram_user_name = $traveler['instagramUserName'];
            $bookingTraveler->tiktok_user_name = $traveler['tiktokUserName'];
            $bookingTraveler->facebook_user_name = $traveler['facebookUserName'];
            $bookingTraveler->emergency_contact1 = $traveler['emergencyContact1'];
            $bookingTraveler->emergency_contact2 = $traveler['emergencyContact2'];
            $bookingTraveler->relationship = $traveler['relationship'];
            $bookingTraveler->relationship_traveler = $traveler['relationship_traveler'];
            $bookingTraveler->drug_status = $traveler['drugStatus'];
            $bookingTraveler->food_status = $traveler['foodStatus'];
            $bookingTraveler->diet = $traveler['diet'];
            $bookingTraveler->meditation = $traveler['meditation'];
            $bookingTraveler->particular = $traveler['particular'];
            $bookingTraveler->note = $traveler['note'];
            $bookingTraveler->flight_number = $traveler['flightNumber'];
            $bookingTraveler->flight_date = $traveler['flightDate'];
            $bookingTraveler->dispatcher_time = $traveler['dispatcherTime'];
            $bookingTraveler->dispatcher_airport = $traveler['dispatcherAirport'];
            $bookingTraveler->arrival_time = $traveler['arrivalTime'];
            $bookingTraveler->arrival_airport = $traveler['arrivalAirport'];

            $bookingTraveler->save();

            $userExists = User::role('Traveler')->where('email', $traveler['emailAddress'])->exists();

            if (! $userExists) {
                User::withoutEvents(function () use ($traveler) {
                    $data = $this->prepareDataForCreatingTravelerUser($traveler);

                    $this->userRepository->createUser($data);
                });
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Travelers updated successfully',
            'data' => $booking,
        ], 200);
    }

    public function prepareDataForCreatingTravelerUser(array $data)
    {
        return [
            'role' => 'Traveler',
            'agent_id' => auth()->id(),
            'first_name' => $data['firstName'],
            'last_name' => $data['lastName'],
            'email' => $data['emailAddress'],
            'contact' => $data['mobileNumber'],
            'emergency_contact1' => $data['emergencyContact1'],
            'emergency_contact2' => $data['emergencyContact2'],
            'profile_photo_path' => $data['travelerImg'],
            'address' => $data['address'],
            'country' => $data['country'],
            'state' => $data['state'],
            'zip' => $data['zipCode'],
            'call_name' => $data['callName'],
            'dob' => $data['dateOfBirth'],
            'gender' => $data['gender'],
            'passport_id' => $data['passportId'],
            'date_of_delivery' => $data['dateOfDelivery'],
            'date_of_expire' => $data['dateOfExpire'],
            'language' => $data['language'],
            'whatsapp' => $data['whatsappNumber'],
            'instagram' => $data['instagramUserName'],
            'tiktok' => $data['tiktokUserName'],
            'facebook' => $data['facebookUserName'],
            'drug_status' => $data['drugStatus'],
            'food_status' => $data['foodStatus'],
            'diet' => $data['diet'],
            'medication' => $data['meditation'],
            'particular' => $data['particular'],
            'note' => $data['note'],
        ];
    }

    public function cancel(Booking $booking)
    {
        $this->bookingRepository->cancelBooking($booking);
    }

    public function complete(Booking $booking)
    {
        $this->bookingRepository->completeBooking($booking);
    }

    public function createBookingNote(Request $request)
    {
        $this->bookingRepository->createBookingNote($request->all());
    }

    public function showPrintTravelerDetails(Booking $booking)
    {
        return inertia('Bookings/PDF/PrintTravelerDetails', [
            'booking' => $booking->load(['package', 'bookingTravelers']),
            'agent' => User::role('Travel Agent')->find($booking->agent_id),
            'traveler' => User::role('Traveler')->find($booking->traveler_id),
        ]);
    }

    public function confirmPayment(Request $request)
    {
        $payment = base64_decode($request->payment);
        $signature = base64_decode($request->signature);
        $custom_fields = base64_decode($request->custom_fields);

        $publickey = env('WEBXPAY_PUBLIC_KEY');
        openssl_public_decrypt($signature, $value, $publickey);

        $signature_status = false;

        if ($value == $payment) {
            $signature_status = true;
        }

        //get payment response in segments
        //payment format: order_id|order_refference_number|date_time_transaction|payment_gateway_used|status_code|comment;
        $responseVariables = explode('|', $payment);

        if ($signature_status == true) {
            $custom_fields_varible = explode('|', $custom_fields);
            $orderId = $responseVariables[0];
            $orderReference = $responseVariables[1];
            $transactionDateTime = $responseVariables[2];
            $status_code = $responseVariables[4];
            $gateway = $responseVariables[5];
            $amount = $responseVariables[6];

            $booking = Booking::whereOrderNo($orderId)->first();

            $status = null;

            if ($status_code == '100 - Request was processed successfully.') {
                $status = 'success';
                $booking->payment_status = 'Paid';
                $redirectUrl = 'https://www.dttravelssrilanka.com/bookings/'.$booking->id.'?status=success';
                $booking->save();
            } else {
                $status = 'failed';
                $redirectUrl = 'https://www.dttravelssrilanka.com/bookings/payment/'.$booking->id.'?status=failed';
            }

            $paymentData = new Payment();
            $paymentData->booking_id = $booking->id;
            $paymentData->status = $status;
            $paymentData->status_code = $status_code;
            $paymentData->amount = $amount;
            $paymentData->transaction_time = $transactionDateTime;
            $paymentData->response = $payment;
            $paymentData->save();

        } else {
            Log::info('Payment verification failed');
        }

        return redirect($redirectUrl);
    }

    public function updateExistingTravelers(Request $request, $booking_id)
    {
        $validator = Validator::make($request->all(), [
            'travelers.*.id' => ['required'],
            'travelers.*.flightNumber' => ['required', 'string', 'max:250', 'min:5'],
            'travelers.*.flightDate' => ['required', 'date'],
            'travelers.*.dispatcherTime' => ['required', 'date_format:H:i'],
            'travelers.*.dispatcherAirport' => ['required', 'string', 'max:250', 'min:5'],
            'travelers.*.arrivalTime' => ['required', 'date_format:H:i'],
            'travelers.*.arrivalAirport' => ['required', 'string', 'max:250', 'min:5'],
        ], [], [
            'travelers.*.id' => 'Traveler',
            'travelers.*.flightNumber' => 'flight number',
            'travelers.*.flightDate' => 'flight date',
            'travelers.*.dispatcherTime' => 'departure time',
            'travelers.*.dispatcherAirport' => 'departure airport',
            'travelers.*.arrivalTime' => 'arrival time',
            'travelers.*.arrivalAirport' => 'arrival airport',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $booking = Booking::findOrFail($booking_id);
        $travelers = $request->travelers;

        foreach ($travelers as $traveler) {
            $user = User::find($traveler['id']);
            $bookingTraveler = new BookingTraveler();
            $bookingTraveler->booking_id = $booking->id;
            $bookingTraveler->created_by = Auth::id();
            $bookingTraveler->first_name = $user->first_name;
            $bookingTraveler->last_name = $user->last_name;
            $bookingTraveler->call_name = $user->call_name;
            $bookingTraveler->date_of_birth = $user->dob;
            $bookingTraveler->gender = $user->gender;
            $bookingTraveler->passport_id = $user->passport_id;
            $bookingTraveler->date_of_delivery = $user->date_of_delivery;
            $bookingTraveler->date_of_expire = $user->date_of_expire;
            $bookingTraveler->language = $user->language;
            $bookingTraveler->traveler_img = $user->profile_photo_path;
            $bookingTraveler->mobile_number = $user->contact;
            $bookingTraveler->whatsapp_number = $user->whatsapp;
            $bookingTraveler->email_address = $user->email;
            $bookingTraveler->address = $user->address;
            $bookingTraveler->country = $user->country;
            $bookingTraveler->state = $user->state;
            $bookingTraveler->zip_code = $user->zip;
            $bookingTraveler->instagram_user_name = $user->instagram;
            $bookingTraveler->tiktok_user_name = $user->tiktok;
            $bookingTraveler->facebook_user_name = $user->facebook;
            $bookingTraveler->emergency_contact1 = $user->emergency_contact1;
            $bookingTraveler->emergency_contact2 = $user->emergency_contact2;
            //            $bookingTraveler->relationship = $user->relationship;
            //            $bookingTraveler->relationship_traveler = $user->relationship_traveler;
            $bookingTraveler->drug_status = $user->drug_status;
            $bookingTraveler->food_status = $user->food_status;
            $bookingTraveler->diet = $user->diet;
            $bookingTraveler->meditation = $user->medication;
            $bookingTraveler->particular = $user->particular;
            $bookingTraveler->note = $user->note;
            $bookingTraveler->flight_number = $traveler['flightNumber'];
            $bookingTraveler->flight_date = $traveler['flightDate'];
            $bookingTraveler->dispatcher_time = $traveler['dispatcherTime'];
            $bookingTraveler->dispatcher_airport = $traveler['dispatcherAirport'];
            $bookingTraveler->arrival_time = $traveler['arrivalTime'];
            $bookingTraveler->arrival_airport = $traveler['arrivalAirport'];
            $bookingTraveler->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Travelers updated successfully',
            'data' => $booking,
        ], 200);
    }

    public function addTravelersForBooking(Request $request, Booking $booking)
    {
        $validator = Validator::make($request->all(), [
            'traveler_id' => ['required'],
            'flight_number' => ['required', 'string', 'max:250', 'min:5'],
            'flight_date' => ['required', 'date'],
            'dispatcher_time' => ['required', 'date_format:H:i'],
            'dispatcher_airport' => ['required', 'string', 'max:250', 'min:5'],
            'arrival_time' => ['required', 'date_format:H:i'],
            'arrival_airport' => ['required', 'string', 'max:250', 'min:5'],
        ], [], [
            'traveler_id' => 'Traveler',
            'flight_number' => 'flight number',
            'flight_date' => 'flight date',
            'dispatcher_time' => 'departure time',
            'dispatcher_airport' => 'departure airport',
            'arrivalTime' => 'arrival time',
            'arrival_airport' => 'arrival airport',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $user = User::find($request->traveler_id);

        $flight_number = $request->flight_number;
        $flight_date = $request->flight_date;
        $dispatcher_time = $request->dispatcher_time;
        $dispatcher_airport = $request->dispatcher_airport;
        $arrival_time = $request->arrival_time;
        $arrival_airport = $request->arrival_airport;

        $booking->traveler_id = $request->traveler_id;
        $booking->save();

        $bookingTraveler = new BookingTraveler();
        $bookingTraveler->booking_id = $booking->id;
        $bookingTraveler->created_by = Auth::id();
        $bookingTraveler->first_name = $user->first_name;
        $bookingTraveler->last_name = $user->last_name;
        $bookingTraveler->call_name = $user->call_name;
        $bookingTraveler->date_of_birth = $user->dob;
        $bookingTraveler->gender = $user->gender;
        $bookingTraveler->passport_id = $user->passport_id;
        $bookingTraveler->date_of_delivery = $user->date_of_delivery;
        $bookingTraveler->date_of_expire = $user->date_of_expire;
        $bookingTraveler->language = $user->language;
        $bookingTraveler->traveler_img = $user->profile_photo_path;
        $bookingTraveler->mobile_number = $user->contact;
        $bookingTraveler->whatsapp_number = $user->whatsapp;
        $bookingTraveler->email_address = $user->email;
        $bookingTraveler->address = $user->address;
        $bookingTraveler->country = $user->country;
        $bookingTraveler->state = $user->state;
        $bookingTraveler->zip_code = $user->zip;
        $bookingTraveler->instagram_user_name = $user->instagram;
        $bookingTraveler->tiktok_user_name = $user->tiktok;
        $bookingTraveler->facebook_user_name = $user->facebook;
        $bookingTraveler->emergency_contact1 = $user->emergency_contact1;
        $bookingTraveler->emergency_contact2 = $user->emergency_contact2;
        $bookingTraveler->drug_status = $user->drug_status;
        $bookingTraveler->food_status = $user->food_status;
        $bookingTraveler->diet = $user->diet;
        $bookingTraveler->meditation = $user->medication;
        $bookingTraveler->particular = $user->particular;
        $bookingTraveler->note = $user->note;
        $bookingTraveler->flight_number = $flight_number;
        $bookingTraveler->flight_date = $flight_date;
        $bookingTraveler->dispatcher_time = $dispatcher_time;
        $bookingTraveler->dispatcher_airport = $dispatcher_airport;
        $bookingTraveler->arrival_time = $arrival_time;
        $bookingTraveler->arrival_airport = $arrival_airport;
        $bookingTraveler->save();
    }
}
