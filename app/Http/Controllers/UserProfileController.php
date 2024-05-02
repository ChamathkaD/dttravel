<?php

namespace App\Http\Controllers;

use App\Actions\Fortify\UpdateFirstLoginUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response;

class UserProfileController extends Controller
{
    /**
     * Display the user profile.
     *
     * This method renders the user profile using the Inertia.js template 'Profile/Show'.
     *
     * @return Response The Inertia response for rendering the profile view.
     */
    public function show()
    {
        return inertia()->render('Profile/Show');
    }

    /**
     * Update the user's profile information.
     *
     * This method uses the specified $updater to update the profile information
     * of the authenticated user based on the data provided in the HTTP request.
     *
     * @param  Request  $request The HTTP request instance.
     * @param  UpdateUserProfileInformation  $updater The updater responsible for updating user profile information.
     * @return void
     */
    public function update(Request $request, UpdateUserProfileInformation $updater)
    {
        $updater->update($request->user(), $request->all());
    }

    /**
     * Remove the user's profile photo.
     *
     * This method deletes the user's profile photo using the `deleteProfilePhoto` method
     * available on the authenticated user. After deletion, the user is redirected back to
     * the previous page with a status code 303 (See Other).
     *
     * @param  Request  $request The HTTP request instance.
     * @return RedirectResponse A redirect response back to the previous page with status code 303.
     */
    public function destroy(Request $request)
    {
        $request->user()->deleteProfilePhoto();

        return back(303);
    }

    /**
     * Update the user's password.
     *
     * This method uses the specified $updater to update the password of the
     * authenticated user based on the data provided in the HTTP request.
     *
     * @param  Request  $request The HTTP request instance.
     * @param  UpdateUserPassword  $updater The updater responsible for updating user passwords.
     * @return void
     */
    public function updatePassword(Request $request, UpdateUserPassword $updater)
    {
        $updater->update($request->user(), $request->all());
    }

    /**
     * Set user's first login password.
     *
     * @param  Request  $request The HTTP request instance.
     * @param  UpdateFirstLoginUserPassword  $updater The updater responsible for updating user passwords.
     * @return void
     */
    public function updateFirstLoginPassword(Request $request, UpdateFirstLoginUserPassword $updater)
    {
        $updater->update($request->user(), $request->all());
    }
}
