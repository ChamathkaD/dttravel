<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\User;

class PrintController extends Controller
{
    public function downloadInvoice(Booking $booking)
    {
        return inertia('Print/Invoice', [
            'booking' => $booking->load(['package', 'bookingTravelers']),
            'agent' => User::role('Travel Agent')->find($booking->agent_id),
            'traveler' => User::role('Traveler')->find($booking->traveler_id),
        ]);
    }

    public function downloadTravelerDetails(Booking $booking)
    {
        return inertia('Print/TravelerDetails', [
            'booking' => $booking->load(['package', 'bookingTravelers']),
            'agent' => User::role('Travel Agent')->find($booking->agent_id),
            'traveler' => User::role('Traveler')->find($booking->traveler_id),
        ]);
    }
}
