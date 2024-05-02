<?php

namespace App\Http\Controllers;

use App\Interfaces\InvitationTokenRepositoryInterface;
use App\Models\User;
use App\Services\UpdateUserAccountService;
use App\Services\UserAccountAuthenticationService;
use App\Services\UserAccountValidationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class UserAccountController extends Controller
{
    private InvitationTokenRepositoryInterface $invitationTokenRepository;

    private UserAccountValidationService $validationService;

    private UpdateUserAccountService $updateService;

    private UserAccountAuthenticationService $authenticationService;

    public function __construct(
        InvitationTokenRepositoryInterface $invitationTokenRepository,
        UserAccountValidationService $validationService,
        UpdateUserAccountService $updateService,
        UserAccountAuthenticationService $authenticationService,
    ) {
        $this->invitationTokenRepository = $invitationTokenRepository;
        $this->validationService = $validationService;
        $this->updateService = $updateService;
        $this->authenticationService = $authenticationService;
    }

    public function showEmailConfirmationView(Request $request)
    {
        return inertia('Auth/EmailConfirmation', [
            'user' => $this->invitationTokenRepository->getUserByToken($request->token),
            'token' => $request->token,
        ]);
    }

    public function create(Request $request)
    {
        return inertia('Auth/Wizard/Register', [
            'user' => $this->invitationTokenRepository->getUserByToken($request->token),
            'countries' => DB::table('countries')->select('name')->get(),
        ]);
    }

    public function stepOne(Request $request)
    {
        $this->validationService->validateStepOne($request);
    }

    public function stepTwo(Request $request)
    {
        $this->validationService->validateStepTwo($request);
    }

    /**
     * Update the user profile based on the provided request data.
     *
     * @param  Request  $request The HTTP request containing the update data.
     * @param  User  $user The user model to be updated.
     * @return RedirectResponse Redirects to the dashboard if login is successful.
     *
     * @throws ValidationException If validation fails.
     */
    public function update(Request $request, User $user)
    {
        // validate the update request
        $this->validationService->validateUpdate($request);

        $request->merge(['address' => $request->input('business_address')]);
        $request->merge(['country' => $request->input('business_country')]);

        // update the user profile using the update service
        $this->updateService->update($user, $request->all());

        // delete the invitation token associated with the user
        $this->invitationTokenRepository->deleteToken($user);

        // attempt to log in the user with the updated credentials
        return $this->authenticationService->attemptLogin($user->email, $request->password);
    }

    public function sendPasswordResetLink(Request $request)
    {
        if ($request->email) {
            Password::sendResetLink($request->only('email'));
        }
    }
}
