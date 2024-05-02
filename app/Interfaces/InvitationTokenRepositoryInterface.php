<?php

namespace App\Interfaces;

use App\Models\User;

interface InvitationTokenRepositoryInterface
{
    public function getUserByToken(string $token);

    public function deleteToken(User $user);
}
