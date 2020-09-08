<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\Perm;
use App\Models\User;
use App\Models\RequestComment;
use Illuminate\Support\Facades\Gate;

class RequestCommentPolicy
{
    /**
     * @param  User  $user
     * @param  RequestComment  $requestComment
     * @return bool
     */
    public function update(User $user, RequestComment $requestComment): bool
    {
        if ($user->perm(Perm::REQUESTS_COMMENTS_EDIT_ALL)) {
            return true;
        }

        return $user->perm(Perm::REQUESTS_COMMENTS_EDIT_OWN) && Gate::allows('owner', $requestComment);
    }

    /**
     * @param  User  $user
     * @param  RequestComment  $requestComment
     * @return bool
     */
    public function delete(User $user, RequestComment $requestComment): bool
    {
        if ($user->perm(Perm::REQUESTS_COMMENTS_DELETE_ALL)) {
            return true;
        }

        return $user->perm(Perm::REQUESTS_COMMENTS_DELETE_OWN) && Gate::allows('owner', $requestComment);
    }
}
