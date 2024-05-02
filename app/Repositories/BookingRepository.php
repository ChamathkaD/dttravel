<?php

namespace App\Repositories;

use App\Interfaces\BookingRepositoryInterface;
use App\Models\AgentPayment;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class BookingRepository implements BookingRepositoryInterface
{
    /**
     * Retrieve all upcoming bookings.
     *
     * This method logs the activity of showing all upcoming bookings and retrieves
     * a collection or array of upcoming bookings with associated agents.
     *
     * @return Collection|array The collection or array of upcoming bookings.
     */
    public function getUpcomingBookings(): Collection|array
    {
        activity()->log('Showed all upcoming bookings');

        if (auth()->user()->hasRole('Travel Agent')) {
            return Booking::with('agent')
                ->where('agent_id', auth()->id())
                ->inProgress()
                ->get();
        }

        return Booking::with('agent')
            ->inProgress()
            ->orderBy('travel_date', 'desc')
            ->get();
    }

    /**
     * Retrieve all completed bookings.
     *
     * This method logs the activity of showing all completed bookings and retrieves
     * a collection or array of completed bookings with associated agents.
     *
     * @return Collection|array The collection or array of completed bookings.
     */
    public function getCompletedBookings(): Collection|array
    {
        activity()->log('Showed all completed bookings');

        if (auth()->user()->hasRole('Travel Agent')) {
            return Booking::with('agent')
                ->where('agent_id', auth()->id())
                ->completed()
                ->get();
        }

        return Booking::with('agent')
            ->completed()
            ->orderBy('travel_date', 'desc')
            ->get();
    }

    /**
     * Retrieve all cancelled bookings.
     *
     * This method logs the activity of showing all cancelled bookings and retrieves
     * a collection or array of cancelled bookings with associated agents.
     *
     * @return Collection|array The collection or array of cancelled bookings.
     */
    public function getCancelBookings(): Collection|array
    {
        activity()->log('Showed all cancelled bookings');

        if (auth()->user()->hasRole('Travel Agent')) {
            return Booking::with('agent')
                ->where('agent_id', auth()->id())
                ->cancel()
                ->get();
        }

        return Booking::with('agent')
            ->cancel()
            ->orderBy('travel_date', 'desc')
            ->get();
    }

    /**
     * Cancel a booking.
     *
     * This method cancels the provided booking by interacting with the booking repository.
     *
     * @param  Booking  $booking The booking to be canceled.
     */
    public function cancelBooking(Booking $booking): void
    {
        $booking->status = Booking::STATUS_CANCELED;
        $booking->save();
    }

    /**
     * Mark a booking as completed.
     *
     * This method updates the status of the provided booking to 'completed'.
     *
     * @param  Booking  $booking The booking to be marked as completed.
     */
    public function completeBooking(Booking $booking): void
    {
        $booking->status = Booking::STATUS_COMPLETED;
        $booking->save();

        $agent = User::find($booking->agent_id);
        // Create agent payment record
        $agent_commission_rate = $agent->agent_commission_rate;
        $booking_total_price = floatval(str_replace(',', '', $booking->total_price));
        $b2b_amount = $booking_total_price * ($agent_commission_rate / 100);

        // Create agent payment record
        $agent_payment = AgentPayment::where('booking_id', $booking->id)->first();
        $agent_payment->booking_id = $booking->id;
        $agent_payment->agent_id = $booking->agent_id;
        $agent_payment->total_sale = $booking_total_price;
        $agent_payment->billing_date = now();
        $agent_payment->b2b_amount = $b2b_amount;
        $agent_payment->save();
    }

    /**
     * create the note and note title for a booking.
     *
     * @param  array  $data The data containing the created note title and note.
     */
    public function createBookingNote(array $data): void
    {
        $booking = Booking::findOrFail($data['booking_id']);
        $booking->bookingNotes()->create($data);
    }

    public function updateBookingPayment(array $data, Booking $booking): void
    {
        $booking->total_price = $data['total_price'];
        $booking->amount = $data['total_price'];
        $booking->net_amount = $data['total_price'];
        $booking->save();
    }
}
