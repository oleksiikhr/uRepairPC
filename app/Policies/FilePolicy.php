<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\Perm;
use App\Models\File;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class FilePolicy
{
    /**
     * @param  User  $user
     * @param  File  $file
     * @return bool
     */
    public function downloadEquipment(User $user, File $file): bool
    {
        if ($user->perm(Perm::EQUIPMENTS_FILES_DOWNLOAD_ALL)) {
            return true;
        }

        return $user->perm(Perm::EQUIPMENTS_FILES_DOWNLOAD_OWN) && Gate::allows('owner', $file);
    }

    /**
     * @param  User  $user
     * @param  File  $file
     * @return bool
     */
    public function updateEquipment(User $user, File $file): bool
    {
        if ($user->perm(Perm::EQUIPMENTS_FILES_EDIT_ALL)) {
            return true;
        }

        return $user->perm(Perm::EQUIPMENTS_FILES_EDIT_OWN) && Gate::allows('owner', $file);
    }

    /**
     * @param  User  $user
     * @param  File  $file
     * @return bool
     */
    public function deleteEquipment(User $user, File $file): bool
    {
        if ($user->perm(Perm::EQUIPMENTS_FILES_DELETE_ALL)) {
            return true;
        }

        return $user->perm(Perm::EQUIPMENTS_FILES_DELETE_OWN) && Gate::allows('owner', $file);
    }

    /**
     * @param  User  $user
     * @param  File  $file
     * @return bool
     */
    public function downloadRequest(User $user, File $file): bool
    {
        if ($user->perm(Perm::REQUESTS_FILES_DOWNLOAD_ALL)) {
            return true;
        }

        return $user->perm(Perm::REQUESTS_FILES_DOWNLOAD_OWN) && Gate::allows('owner', $file);
    }

    /**
     * @param  User  $user
     * @param  File  $file
     * @return bool
     */
    public function updateRequest(User $user, File $file): bool
    {
        if ($user->perm(Perm::REQUESTS_FILES_EDIT_ALL)) {
            return true;
        }

        return $user->perm(Perm::REQUESTS_FILES_EDIT_OWN) && Gate::allows('owner', $file);
    }

    /**
     * @param  User  $user
     * @param  File  $file
     * @return bool
     */
    public function deleteRequest(User $user, File $file): bool
    {
        if ($user->perm(Perm::REQUESTS_FILES_DELETE_ALL)) {
            return true;
        }

        return $user->perm(Perm::REQUESTS_FILES_DELETE_OWN) && Gate::allows('owner', $file);
    }
}
