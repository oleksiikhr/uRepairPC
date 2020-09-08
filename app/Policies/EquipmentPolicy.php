<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\Perm;
use App\Models\User;
use App\Models\Equipment;
use Illuminate\Support\Facades\Gate;

class EquipmentPolicy
{
    /**
     * @param  User  $user
     * @param  Equipment  $equipment
     * @return bool
     */
    public function show(User $user, Equipment $equipment): bool
    {
        if ($user->perm(Perm::EQUIPMENTS_VIEW_ALL)) {
            return true;
        }

        return $user->perm(Perm::EQUIPMENTS_VIEW_OWN) && Gate::allows('owner', $equipment);
    }

    /**
     * @param  User  $user
     * @param  Equipment  $equipment
     * @return bool
     */
    public function update(User $user, Equipment $equipment): bool
    {
        if ($user->perm(Perm::EQUIPMENTS_EDIT_ALL)) {
            return true;
        }

        return $user->perm(Perm::EQUIPMENTS_EDIT_OWN) && Gate::allows('owner', $equipment);
    }

    /**
     * @param  User  $user
     * @param  Equipment  $equipment
     * @return bool
     */
    public function delete(User $user, Equipment $equipment): bool
    {
        if ($user->perm(Perm::EQUIPMENTS_DELETE_ALL)) {
            return true;
        }

        return $user->perm(Perm::EQUIPMENTS_DELETE_OWN) && Gate::allows('owner', $equipment);
    }
}
