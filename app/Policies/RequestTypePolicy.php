<?php declare(strict_types=1);

namespace App\Policies;

use App\Enums\Perm;
use App\Models\User;
use App\Models\RequestType;
use Illuminate\Support\Facades\Gate;

class RequestTypePolicy
{
    /**
     * @param  User  $user
     * @param  RequestType  $requestType
     * @return bool
     */
    public function update(User $user, RequestType $requestType): bool
    {
        if ($user->perm(Perm::REQUESTS_CONFIG_EDIT_ALL)) {
            return true;
        }

        return $user->perm(Perm::REQUESTS_CONFIG_EDIT_OWN) && Gate::allows('owner', $requestType);
    }

    /**
     * @param  User  $user
     * @param  RequestType  $requestType
     * @return bool
     */
    public function delete(User $user, RequestType $requestType): bool
    {
        if ($requestType->default) {
            // TODO throw app.request_type.destroy_default
            return false;
        }

        if ($user->perm(Perm::REQUESTS_CONFIG_DELETE_ALL)) {
            return true;
        }

        return $user->perm(Perm::REQUESTS_CONFIG_DELETE_OWN) && Gate::allows('owner', $requestType);
    }
}
