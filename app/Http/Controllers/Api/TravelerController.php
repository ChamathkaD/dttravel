<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Interfaces\UserRepositoryInterface;
use App\Models\User;

class TravelerController extends Controller
{
    private UserRepositoryInterface $userRepository;

    public function __construct(
        UserRepositoryInterface $userRepository,
    ) {
        $this->userRepository = $userRepository;
    }

    public function index($agent_id = null)
    {
        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'Travelers retrieved successfully',
            'data' => $this->userRepository->getTravelersByAgent($agent_id),
        ]);
    }

    public function getTravelerById($id = null)
    {
        $user = User::where('id', $id)->first();

        return response()->json([
            'success' => true,
            'message' => 'Traveler Found!',
            'data' => $user,
        ], 200);
    }
}
