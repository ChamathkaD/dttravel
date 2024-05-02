<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BookingPlaced extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The booking instance.
     */
    public Booking $booking;

    /**
     * Create a new message instance.
     */
    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    /**
     * Build the message.
     */
    public function build(): self
    {
        return $this->subject('Booking Placed - Order No: #'.$this->booking->order_no)
            ->view('emails.booking-notice');
    }
}
