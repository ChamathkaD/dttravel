<?php

namespace App\Events;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Events\Login;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UpdateLastLogin
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public User $user)
    {
    }

    /**
     * Handle the event.
     *
     * @return void
     */
    public function handle(Login $event)
    {
        try {
            $user = $event->user;
            $user->last_login_at = Carbon::now();
            $user->status = User::STATUS_ACTIVE;
            $user->save();
        } catch (\Throwable $throwable) {
            report($throwable);
        }
    }
}
