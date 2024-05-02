<?php

namespace App\Repositories;

use App\Interfaces\InvitationTokenRepositoryInterface;
use App\Models\InvitationToken;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class InvitationTokenRepository implements InvitationTokenRepositoryInterface
{
    /**
     * Retrieve a user based on the provided invitation token.
     *
     * This function fetches a user associated with a given invitation token.
     *
     * @param  string  $token The invitation token used to identify the user.
     * @return User|null The User model associated with the provided token, or null if not found.
     *
     * @throws ModelNotFoundException If the user is not found.
     */
    public function getUserByToken(string $token): ?User
    {
        $invitationToken = InvitationToken::where('token', $token)->firstOrFail();

        return $invitationToken->user;
    }

    /**
     * Delete the invitation token associated with the given user.
     *
     * This function removes the invitation token associated with a specified user.
     *
     * @param  User  $user The user for whom the invitation token should be deleted.
     */
    public function deleteToken(User $user): void
    {
        $user->invitationToken()->delete();
    }
}
