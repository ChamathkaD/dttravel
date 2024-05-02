<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewResetPassword extends Notification
{
    /**
     * Create a notification instance.
     *
     * @return void
     */
    public function __construct(public string $token)
    {
    }

    /**
     * Get the notification's channels.
     *
     * @param  mixed  $notifiable
     * @return array|string
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Your Password Reset Link')
            ->view('emails.auth.reset-password', ['resetUrl' => url(config('app.url').route('password.reset', $this->token, false))]);
    }
}
