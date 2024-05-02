<?php

namespace App\Jobs;

use App\Mail\ContactFormSubmitted;
use App\Mail\NewContactReceived;
use App\Models\Contact;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendContactNotificationEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public Contact $contact)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->contact->email)->send(new ContactFormSubmitted($this->contact));
        Mail::to('inquiries@dttravelssrilanka.com')->send(new NewContactReceived($this->contact));
    }
}
