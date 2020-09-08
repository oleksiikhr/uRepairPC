<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\Perm;
use App\Models\User;
use App\Models\RequestStatus;
use Illuminate\Support\Facades\Gate;

class RequestStatusPolicy
{
    /**
     * @param  User  $user
     * @param  RequestStatus  $requestStatus
     * @return bool
     */
    public function update(User $user, RequestStatus $requestStatus): bool
    {
        if ($user->perm(Perm::REQUESTS_CONFIG_EDIT_ALL)) {
            return true;
        }

        return $user->perm(Perm::REQUESTS_CONFIG_EDIT_OWN) && Gate::allows('owner', $requestStatus);
    }

    /**
     * @param  User  $user
     * @param  RequestStatus  $requestStatus
     * @return bool
     */
    public function delete(User $user, RequestStatus $requestStatus): bool
    {
        if ($requestStatus->default) {
            // TODO throw app.request_status.destroy_default
            return false;
        }

        if ($user->perm(Perm::REQUESTS_CONFIG_DELETE_ALL)) {
            return true;
        }

        return $user->perm(Perm::REQUESTS_CONFIG_DELETE_OWN) && Gate::allows('owner', $requestStatus);
    }
}
