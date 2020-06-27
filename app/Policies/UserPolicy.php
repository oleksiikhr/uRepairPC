<?php declare(strict_types=1);

namespace App\Policies;

use App\Enums\Perm;
use App\Models\User;

class UserPolicy
{
    /**
     * @param  User  $author
     * @param  User  $user
     * @return bool
     */
    public function delete(User $author, User $user): bool
    {
        if ($user->id === 1 || $author->id === $user->id) {
            return false;
        }

        return $author->perm(Perm::USERS_DELETE_ALL);
    }

    /**
     * @param  User  $author
     * @param  User  $user
     * @return bool
     */
    public function updateRoles(User $author, User $user): bool
    {
        if ($user->id === 1) {
            return false;
        }

        return $author->perm(Perm::ROLES_EDIT_ALL);
    }
}
