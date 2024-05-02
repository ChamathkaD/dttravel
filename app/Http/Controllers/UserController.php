<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Interfaces\RoleRepositoryInterface;
use App\Interfaces\UserRepositoryInterface;
use App\Jobs\SendInvitationEmail;
use App\Models\InvitationToken;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    private UserRepositoryInterface $userRepository;

    private RoleRepositoryInterface $roleRepository;

    public function __construct(
        UserRepositoryInterface $userRepository,
        RoleRepositoryInterface $roleRepository
    ) {
        $this->userRepository = $userRepository;
        $this->roleRepository = $roleRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->trashed) {
            $users = User::onlyTrashed()->get();

            activity()
                ->log('Showed trashed users');
        } else {
            if (request()->roleName == 'staff') {

                if (auth()->user()->hasRole('Staff Member')) {
                    abort(403, 'Unauthorized action.');
                }

                $users = $this->userRepository->getAllStaffMemberUsers();
            } elseif (request()->roleName == 'agent') {
                $users = $this->userRepository->getAllTravelAgentUsers();
            } else {

                if (auth()->user()->hasRole('Staff Member')) {
                    abort(403, 'Unauthorized action.');
                }

                $users = $this->userRepository->getAllUsersWithRole();
            }
        }

        $roleNames = $this->roleRepository->getAllRoleNames();

        return inertia('Users/UserList')->with(compact('users', 'roleNames'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        if ($request->role === 'Traveler') {
            User::withoutEvents(function () use ($request) {
                $this->userRepository->createUser($request->validated());
            });
        } else {
            $this->userRepository->createUser($request->validated());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $this->userRepository->updateUser($request->validated(), $user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $this->userRepository->deleteUser($user);
    }

    public function enableUser(User $user)
    {
        $this->userRepository->enableUser($user);
    }

    public function disableUser(User $user)
    {
        $this->userRepository->disableUser($user);
    }

    public function resendInvitationMail(User $user)
    {
        // Remove the invitation token if it exists for the user.
        if ($user->invitationToken()->exists()) {
            $user->invitationToken->delete();
        }

        // update the invitation token table
        $invitation_token = new InvitationToken();
        $invitation_token->token = $invitation_token->generateInvitationToken();
        // save the invitation token in the user's relationship
        $user->invitationToken()->save($invitation_token);

        // send invitation email
        SendInvitationEmail::dispatch($user, $invitation_token->token);
    }

    public function restore($user_id = null)
    {
        $this->userRepository->restoreUser($user_id);
    }

    public function fDelete($user_id = null)
    {
        $this->userRepository->forceDelete($user_id);
    }

    public function apiStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => ['required', 'string', 'max:50', 'min:2'],
            'last_name' => ['required', 'string', 'max:50', 'min:2'],
            'email' => ['required', 'string', 'email', 'max:100'],
            'contact' => ['required', 'phone:INTERNATIONAL'],
            'whatsapp' => ['required', 'phone:INTERNATIONAL'],
            'address' => ['required', 'string'],
            'country' => ['required', 'string'],
            'business_city' => ['required', 'string'],
            'zip' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $existUser = User::where('email', $request->email)->first();
        $token = null;

        if ($existUser && $existUser->role_name === 'Traveler') {
            $user = $this->userRepository->updateUser($request->all(), $existUser);
            $token = $existUser->createToken('AuthToken')->plainTextToken;
        } else {
            $user = User::withoutEvents(function () use ($request) {
                return $this->userRepository->createUser($request->all());
            });
            $token = $user->createToken('AuthToken')->plainTextToken;
        }

        return response()->json([
            'user' => $user,
            'token' => $token,
        ]);
    }
}
