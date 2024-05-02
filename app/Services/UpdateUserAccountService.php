<?php

namespace App\Services;

use App\Models\User;

class UpdateUserAccountService
{
    /**
     * Update the user model with the provided data and handle photo and logo updates if specified.
     *
     * @param  User  $user The user model to be updated.
     * @param  array  $data The data to update the user with.
     */
    final public function update(User $user, array $data): void
    {
        // update the user model with the provided data
        $user->update($data);

        // if a new profile photo is specified, update it
        if (isset($data['photo'])) {
            $user->updateProfilePhoto($data['photo']);
        }

        // if a new logo is specified, update it
        if (isset($data['logo'])) {
            $user->updatePhoto($data['logo'], 'business_logo_path', 'company-logos');
        }

        $user->status = User::STATUS_PENDING;

        // save the changes to the user model
        $user->save();
    }
}
