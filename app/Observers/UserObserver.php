<?php

namespace App\Observers;

use App\Jobs\SendInvitationEmail;
use App\Models\InvitationToken;
use App\Models\User;

class UserObserver
{
    /**
     * Handle the creation of a new user.
     *
     * @param  User  $user The user that was created.
     */
    public function created(User $user): void
    {
        // update the invitation token table
        $invitation_token = new InvitationToken();
        $invitation_token->token = $invitation_token->generateInvitationToken();
        // save the invitation token in the user's relationship
        $user->invitationToken()->save($invitation_token);

        // send invitation email
        SendInvitationEmail::dispatch($user, $invitation_token->token);
    }
}
