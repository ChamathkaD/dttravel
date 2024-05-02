<?php

namespace App\Interfaces;

use App\Models\Booking;

interface BookingRepositoryInterface
{
    public function getUpcomingBookings();

    public function getCompletedBookings();

    public function getCancelBookings();

    public function cancelBooking(Booking $booking);

    public function completeBooking(Booking $booking);

    public function createBookingNote(array $data);

    public function updateBookingPayment(array $data, Booking $booking);
}
