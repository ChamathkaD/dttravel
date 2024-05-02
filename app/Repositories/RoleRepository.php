<?php

namespace App\Repositories;

use App\Interfaces\RoleRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Spatie\Permission\Models\Role;

class RoleRepository implements RoleRepositoryInterface
{
    /**
     * Get all role names available in the system.
     */
    public function getAllRoleNames(): Collection|array
    {
        return Role::pluck('name')->toArray();
    }
}
