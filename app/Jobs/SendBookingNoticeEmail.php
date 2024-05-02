<?php

namespace App\Jobs;

use App\Mail\BookingPlaced;
use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendBookingNoticeEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The email address of the recipient.
     */
    public string $email;

    /**
     * The booking instance.
     */
    public Booking $booking;

    /**
     * Create a new job instance.
     */
    public function __construct(string $email, Booking $booking)
    {
        $this->email = $email;
        $this->booking = $booking;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->email)->send(new BookingPlaced($this->booking));
    }
}
