<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\Perm;
use App\Models\User;
use App\Models\EquipmentType;
use Illuminate\Support\Facades\Gate;

class EquipmentTypePolicy
{
    /**
     * @param  User  $user
     * @param  EquipmentType  $equipmentType
     * @return bool
     */
    public function update(User $user, EquipmentType $equipmentType): bool
    {
        if ($user->perm(Perm::EQUIPMENTS_CONFIG_EDIT_ALL)) {
            return true;
        }

        return $user->perm(Perm::EQUIPMENTS_CONFIG_EDIT_OWN) && Gate::allows('owner', $equipmentType);
    }

    /**
     * @param  User  $user
     * @param  EquipmentType  $equipmentType
     * @return bool
     */
    public function delete(User $user, EquipmentType $equipmentType): bool
    {
        if ($user->perm(Perm::EQUIPMENTS_CONFIG_DELETE_ALL)) {
            return true;
        }

        return $user->perm(Perm::EQUIPMENTS_CONFIG_DELETE_OWN) && Gate::allows('owner', $equipmentType);
    }
}
