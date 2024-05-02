<?php

namespace App\Jobs;

use App\Mail\PaymentInvited;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendPaymentInvitationEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public Booking $booking)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->booking->traveler_id
            ? $email = User::where('id', $this->booking->traveler_id)->first('email')
            : $email = User::where('id', $this->booking->agent_id)->first('email');

        $url = url('https://dttravelssrilanka.com'.route('download-invoice', $this->booking->id, false));
        $payment_url = config('app.frontend_url').'/bookings/payment/'.$this->booking->id;

        Mail::to($email)
            ->send(new PaymentInvited($this->booking, $url, $payment_url));
    }
}
