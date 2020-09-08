<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\Perm;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use App\Models\Request as RequestModel;

class RequestPolicy
{
    /**
     * @param  User  $user
     * @param  RequestModel  $requestModel
     * @return bool
     */
    public function show(User $user, RequestModel $requestModel): bool
    {
        if ($user->perm(Perm::REQUESTS_VIEW_ALL)) {
            return true;
        }

        if ($user->perm(Perm::REQUESTS_VIEW_OWN) && Gate::allows('owner', $requestModel)) {
            return true;
        }

        return $user->perm(Perm::REQUESTS_VIEW_ASSIGN) && Gate::allows('assign', $requestModel);
    }

    /**
     * @param  User  $user
     * @param  RequestModel  $requestModel
     * @return bool
     */
    public function update(User $user, RequestModel $requestModel): bool
    {
        if ($user->perm(Perm::REQUESTS_EDIT_ALL)) {
            return true;
        }

        if ($user->perm(Perm::REQUESTS_EDIT_OWN) && Gate::allows('owner', $requestModel)) {
            return true;
        }

        return $user->perm(Perm::REQUESTS_EDIT_ASSIGN) && Gate::allows('assign', $requestModel);
    }

    /**
     * @param  User  $user
     * @param  RequestModel  $requestModel
     * @return bool
     */
    public function delete(User $user, RequestModel $requestModel): bool
    {
        if ($user->perm(Perm::REQUESTS_DELETE_ALL)) {
            return true;
        }

        if ($user->perm(Perm::REQUESTS_DELETE_OWN) && Gate::allows('owner', $requestModel)) {
            return true;
        }

        return $user->perm(Perm::REQUESTS_DELETE_ASSIGN) && Gate::allows('assign', $requestModel);
    }
}
