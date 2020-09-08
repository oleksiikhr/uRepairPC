<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\Perm;
use App\Models\User;
use App\Models\RequestPriority;
use Illuminate\Support\Facades\Gate;

class RequestPriorityPolicy
{
    /**
     * @param  User  $user
     * @param  RequestPriority  $requestPriority
     * @return bool
     */
    public function update(User $user, RequestPriority $requestPriority): bool
    {
        if ($user->perm(Perm::REQUESTS_CONFIG_EDIT_ALL)) {
            return true;
        }

        return $user->perm(Perm::REQUESTS_CONFIG_EDIT_OWN) && Gate::allows('owner', $requestPriority);
    }

    /**
     * @param  User  $user
     * @param  RequestPriority  $requestPriority
     * @return bool
     */
    public function delete(User $user, RequestPriority $requestPriority): bool
    {
        if ($requestPriority->default) {
            // TODO throw app.request_priority.destroy_default
            return false;
        }

        if ($user->perm(Perm::REQUESTS_CONFIG_DELETE_ALL)) {
            return true;
        }

        return $user->perm(Perm::REQUESTS_CONFIG_DELETE_OWN) && Gate::allows('owner', $requestPriority);
    }
}
