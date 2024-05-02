<?php

namespace App\Repositories;

use App\Interfaces\UserRepositoryInterface;
use App\Jobs\SendAgentInvitationEmail;
use App\Models\AgentPayment;
use App\Models\TravelerRelation;
use App\Models\User;
use Exception;
use Ichtrojan\Otp\Otp;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserRepository implements UserRepositoryInterface
{
    /**
     * Get all users with their associated roles.
     *
     * This method retrieves all users along with their assigned roles,
     * ordered by the latest user records.
     *
     * @return Collection|User[] A collection of users with their associated roles.
     */
    public function getAllUsersWithRole(): Collection|array
    {
        activity()->log('Showed all users with roles');

        return User::with('roles')
            ->latest()
            ->get();
    }

    /**
     * Get all users with the 'Travel Agent' role.
     *
     * This method retrieves all users who have been assigned the 'Travel Agent' role,
     * ordered by the latest user records.
     *
     * @return Collection|User[] A collection of users with the 'Travel Agent' role.
     */
    public function getAllTravelAgentUsers(): Collection|array
    {
        activity()->log('Showed all travel agent users');

        return User::role('Travel Agent')
            ->withCount('agentBookings')
            ->latest()
            ->get();
    }

    /**
     * Get all users with the 'Staff Member' role.
     *
     * This method retrieves all users who have been assigned the 'Staff Member' role,
     * ordered by the latest user records.
     *
     * @return Collection|User[] A collection of users with the 'Staff Member' role.
     */
    public function getAllStaffMemberUsers(): Collection|array
    {
        activity()->log('Showed all staff member users');

        return User::role('Staff Member')
            ->latest()
            ->get();
    }

    /**
     * Get all traveler users with their relations and country flags.
     *
     * @return \Illuminate\Support\Collection|array
     */
    public function getAllTravelerUsers(): Collection|array
    {
        activity()->log('Showed all travelers');
        /**
         * Retrieve a collection of traveler users with their relations.
         */
        if (auth()->check() && auth()->user()->hasRole('Travel Agent')) {
            $users = User::role('Traveler')
                ->where('agent_id', auth()->id())
                ->latest()
                ->with(['travelerRelations', 'agent', 'travelerBookings'])
                ->withCount('travelerBookings')
                ->withoutTrashed()
                ->get();
        } else {
            $users = User::role('Traveler')
                ->latest()
                ->with(['travelerRelations', 'agent', 'travelerBookings'])
                ->withCount('travelerBookings')
                ->withoutTrashed()
                ->get();
        }

        return $users->each(function ($user) {
            /**
             * Attach country flag to the user based on the country ISO code.
             */
            if ($user->country) {
                /**
                 * Retrieve ISO code of the country from the 'countries' table.
                 */
                $country_iso = DB::table('countries')
                    ->where('name', $user->country)
                    ->value('iso');

                $country_iso = strtolower($country_iso);

                /**
                 * Generate the asset URL for the country flag SVG.
                 */
                //                $user->countryFlag = asset("vendor/blade-flags/country-{$country_iso}.svg");
                $user->countryFlag = 'https://flagcdn.com/'.$country_iso.'.svg';
            }

            /**
             * Transform traveler relations data.
             */
            $user->travelerRelations->transform(function ($data) {
                return [
                    'id' => $data->id,
                    /**
                     * Retrieve the full name of the traveler based on their role and ID.
                     */
                    'traveler' => User::role('Traveler')->withoutTrashed()->find($data->traveler_id)?->full_name,
                    'relation_type' => $data->relationship,
                ];
            });
        });
    }

    /**
     * Create a new user, assign a role.
     *
     * @param  array  $data The user data to create the user.
     */
    public function createUser(array $data): User
    {
        $data['status'] = User::STATUS_INVITED;

        $user = User::create($data);

        // if a new profile photo is specified, update it
        if (isset($data['photo'])) {
            $user->updateProfilePhoto($data['photo']);
        }

        // if a new logo is specified, update it
        if (isset($data['logo'])) {
            $user->updatePhoto($data['logo'], 'business_logo_path', 'company-logos');
        }

        if (isset($data['role'])) {
            // assign role
            $user->assignRole($data['role']);
        }

        $this->createTravelerRelationship($data, $user);

        activity()
            ->performedOn($user)
            ->withProperties(['role' => $data['role']])
            ->log('Created User');

        return $user;
    }

    /**
     * Update the user information and roles.
     *
     * @param  array  $data The data to update the user.
     *   Example:
     *   [
     *       'name' => 'John Doe',
     *       'email' => 'john@example.com',
     *       'role' => 'admin',
     *       // Add other fields as needed
     *   ]
     * @param  User  $user The user instance to update.
     */
    public function updateUser(array $data, User $user): User
    {
        // update user information
        $user->update($data);

        // if a new profile photo is specified, update it
        if (isset($data['photo'])) {
            $user->updateProfilePhoto($data['photo']);
        }

        // if a new logo is specified, update it
        if (isset($data['logo'])) {
            $user->updatePhoto($data['logo'], 'business_logo_path', 'company-logos');
        }

        if (isset($data['role_name'])) {
            // remove all existing roles
            $user->roles()->detach();

            // assign a new role to the user
            $user->assignRole($data['role_name']);
        }

        $this->createTravelerRelationship($data, $user);

        activity()
            ->performedOn($user)
            ->withProperties(['role' => $user->role_name])
            ->log('Updated User');

        return $user;
    }

    /**
     * Delete a user.
     *
     * @param  User  $user The user to be deleted.
     */
    public function deleteUser(User $user): void
    {
        $user->delete();

        activity()
            ->performedOn($user)
            ->log('Deleted User into trash');
    }

    /**
     * Send an invitation email to an agent user.
     *
     * This method generates a random password for the agent user, updates
     * the user's password in the database, and dispatches a job to send
     * an invitation email with the login credentials.
     *
     * @param  User  $user The agent user to whom the invitation will be sent.
     */
    public function sendInvitationMailToAgent(User $user): void
    {
        // generate random password for agent user
        $random_password = Str::password(10);

        // Update the user's password in the database
        $user->password = Hash::make($random_password);
        $user->save();

        // Dispatch a job to send the invitation email
        SendAgentInvitationEmail::dispatch($user, $random_password);

        activity()
            ->performedOn($user)
            ->log('Send invitation mail to traveler agent');
    }

    /**
     * Get the names and relationships of traveler relations for a given traveler.
     *
     * @param  User  $traveler The traveler for whom relations are retrieved.
     * @return \Illuminate\Support\Collection
     *  The collection containing an array for each traveler relation with the following structure:
     *  - 'id': The identifier of the traveler relation.
     *  - 'traveler': The User model representing the related traveler (filtered by the 'Traveler' role).
     *  - 'relation_type': The type of relationship with the traveler.
     */
    public function getTravelerRelationNameAndRelationship(User $traveler): \Illuminate\Support\Collection
    {
        return $traveler->travelerRelations->map(function ($data) {
            return [
                'id' => $data->id,
                'traveler' => User::role('Traveler')->find($data->traveler_id),
                'relation_type' => $data->relationship,
            ];
        });
    }

    /**
     * Generate a One-Time Password (OTP) for the given email address.
     *
     * This method uses the Otp class to generate an OTP token for the specified email.
     *
     * @param  string  $email The email address for which to generate the OTP.
     * @return string The generated OTP token.
     *
     * @throws Exception If there is an issue generating the OTP token.
     */
    public function generateOTP(string $email): string
    {
        $otp = new Otp;
        // Generate an OTP token with 4 digits that expires in 5 minutes
        $otp_token = $otp->generate($email, 'numeric', 4, 5);

        // Return the generated OTP token
        return $otp_token->token;
    }

    /**
     * Validate the provided OTP token for the specified email.
     *
     * @param  string  $token The OTP token to be validated.
     * @param  string  $email The email associated with the OTP token.
     */
    public function validateOTP(string $token, string $email)
    {
        // Create a new instance of the Otp model.
        $otp = new Otp;

        // Validate the provided OTP token for the specified email using the Otp model.
        return $otp->validate($email, $token);
    }

    /**
     * Create traveler relationships for the given user based on provided data.
     *
     * @param  array  $data An array containing traveler relationship data.
     * @param  User  $user The user for whom traveler relationships are being created.
     */
    protected function createTravelerRelationship(array $data, User $user): void
    {
        //Check if traveler relationship data is provided.
        if (isset($data['traveler_relationships'])) {
            // Iterate through provided traveler relationship data and create relationships.
            foreach ($data['traveler_relationships'] as $relation_data) {
                // Check if both traveler ID and relationship type are not null.
                if ($relation_data['traveler'] !== null && $relation_data['traveler_relation'] !== null) {
                    // Create a new TravelerRelation instance and save it to the database.
                    $traveler_relation = new TravelerRelation();
                    $traveler_relation->user_id = $user->id;
                    $traveler_relation->traveler_id = $relation_data['traveler'];
                    $traveler_relation->relationship = $relation_data['traveler_relation'];
                    $traveler_relation->save();
                }
            }
        }
    }

    /**
     * Get the total number of completed bookings associated with the given agent user.
     *
     * @param  User  $user The agent user for whom to retrieve the total bookings.
     * @return int  The total number of bookings for the agent.
     */
    public function getTotalAgentBooking(User $user): int
    {
        return $user->agentBookings()
            ->count();
    }

    /**
     * Get the agent bookings with associated traveler information for the given agent user.
     *
     * @param  User  $user The agent user for whom to retrieve bookings.
     * @return \Illuminate\Support\Collection  A collection of agent bookings with traveler details.
     */
    public function getAgentBookingsWithTraveler(User $user): \Illuminate\Support\Collection
    {
        return $user->agentBookings->transform(function ($booking) {
            return [
                'id' => $booking->id,
                'order_no' => $booking->order_no,
                'created_at' => $booking->created_at,
                'traveler' => User::role('Traveler')->find($booking->traveler_id),
                'status' => $booking->status,
                'payment_status' => $booking->payment_status,
            ];
        });
    }

    /**
     * Enable a user.
     *
     * If the user does not have the 'Super Administrator' role, the user's 'is_ban'
     * attribute will be set to 0, indicating that the user is not banned. The changes
     * will be saved to the database.
     *
     * @param  User  $user The user to be enabled.
     * @return void An Inertia response after the user is enabled.
     */
    public function enableUser(User $user): void
    {
        if (! $user->hasRole('Super Administrator')) {
            $user->is_ban = 0;
            $user->status = User::STATUS_ACTIVE;
            $user->save();
        }

        activity()
            ->performedOn($user)
            ->log('Enabled User');
    }

    /**
     * Disable a user.
     *
     * If the user does not have the 'Super Administrator' role, the user's 'is_ban'
     * attribute will be set to 1, indicating that the user is banned. The changes
     * will be saved to the database.
     *
     * @param  User  $user The user to be disabled.
     * @return void  An Inertia response after the user is disabled.
     */
    public function disableUser(User $user): void
    {
        if (! $user->hasRole('Super Administrator')) {
            $user->is_ban = 1;
            $user->status = User::STATUS_INACTIVE;
            $user->save();
        }

        activity()
            ->performedOn($user)
            ->log('Disabled User');
    }

    /**
     * Restore a soft-deleted user by their ID.
     *
     * This method restores a user that has been soft-deleted. Soft deletion is a mechanism
     * where records are not permanently deleted from the database but are marked as "trashed"
     * or "soft-deleted." This function specifically restores a user by their unique identifier.
     *
     * @param  string  $user_id The unique identifier of the user to be restored.
     */
    public function restoreUser(string $user_id): void
    {
        User::onlyTrashed()->where('id', $user_id)->restore();

        $user = User::find($user_id);

        activity()
            ->performedOn($user)
            ->log('Restored User');
    }

    /**
     * Permanently delete a user along with related data.
     *
     * This method performs a force delete on a user, removing them permanently from the
     * database. It also takes care of additional cleanup tasks such as removing the
     * invitation token, detaching roles, and deleting associated profile photos and business logos.
     *
     * @param  string  $user_id The unique identifier of the user to be permanently deleted.
     *
     * @throws ModelNotFoundException If the specified user is not found.
     */
    public function forceDelete(string $user_id): void
    {
        // Get user
        $user = User::onlyTrashed()->findOrFail($user_id);

        // Remove the invitation token if it exists for the user.
        if ($user->invitationToken()->exists()) {
            $user->invitationToken->delete();
        }

        // Check and remove roles from the user
        if ($user->hasAnyRole()) {
            $user->roles()->detach();
        }

        // Delete the user's profile photo
        if ($user->profile_photo_path) {
            $user->deleteProfilePhoto();
        }

        // Delete the user's business logo
        if ($user->business_logo_path) {
            $user->deletePhoto($user->business_logo_path, 'business_logo_path');
        }

        // Delete the user
        $user->forceDelete();

        activity()
            ->performedOn($user)
            ->withProperties(['user' => $user])
            ->log('Deleted User Permanently');
    }

    public function getTotalSales(User $user): string
    {
        return number_format($user->agentBookings()->sum('total_price'), 2);
    }

    public function getTotalIncome(User $user): string
    {
        $total_price = $user->agentBookings()->sum('total_price');

        $income = $total_price * ($user->agent_commission_rate / 100);

        return number_format($income, 2);
    }

    public function getAgentPayments(User $user): Collection
    {
        return AgentPayment::where('agent_id', $user->id)->get();
    }

    public function getTravelersByAgent(string $agent_id): \Illuminate\Support\Collection
    {
        return User::role('Traveler')
            ->latest()
            ->where('agent_id', $agent_id)
            ->withoutTrashed()
            ->get()
            ->pluck('full_name', 'id');
    }
}
