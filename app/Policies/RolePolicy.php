<?php declare(strict_types=1);

namespace App\Policies;

use App\Enums\Perm;
use App\Models\Role;
use App\Models\User;

class RolePolicy
{
    /**
     * @param  User  $user
     * @param  Role  $role
     * @return bool
     */
    public function updatePermissions(User $user, Role $role): bool
    {
        if ($role->id === 1) {
            return false;
        }

        return $user->perm(Perm::ROLES_EDIT_ALL);
    }

    /**
     * @param  User  $user
     * @param  Role  $role
     * @return bool
     */
    public function delete(User $user, Role $role): bool
    {
        if ($role->id === 1) {
            return false;
        }

        return $user->perm(Perm::ROLES_EDIT_ALL);
    }
}
