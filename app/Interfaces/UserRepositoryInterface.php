<?php

namespace App\Interfaces;

use App\Models\User;

interface UserRepositoryInterface
{
    public function getAllUsersWithRole();

    public function getAllTravelAgentUsers();

    public function getAllStaffMemberUsers();

    public function getAllTravelerUsers();

    public function createUser(array $data);

    public function updateUser(array $data, User $user);

    public function deleteUser(User $user);

    public function sendInvitationMailToAgent(User $user);

    public function getTravelerRelationNameAndRelationship(User $traveler);

    public function generateOTP(string $email);

    public function validateOTP(string $token, string $email);

    public function getTotalAgentBooking(User $user);

    public function getAgentBookingsWithTraveler(User $user);

    public function enableUser(User $user);

    public function disableUser(User $user);

    public function restoreUser(string $user_id);

    public function forceDelete(string $user_id);

    public function getTotalSales(User $user);

    public function getTotalIncome(User $user);

    public function getAgentPayments(User $user);

    public function getTravelersByAgent(string $agent_id);
}
