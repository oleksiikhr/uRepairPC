<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\Perm;
use App\Models\User;
use App\Models\EquipmentModel;
use Illuminate\Support\Facades\Gate;

class EquipmentModelPolicy
{
    /**
     * @param  User  $user
     * @param  EquipmentModel  $equipmentModel
     * @return bool
     */
    public function update(User $user, EquipmentModel $equipmentModel): bool
    {
        if ($user->perm(Perm::EQUIPMENTS_CONFIG_EDIT_ALL)) {
            return true;
        }

        return $user->perm(Perm::EQUIPMENTS_CONFIG_EDIT_OWN) && Gate::allows('owner', $equipmentModel);
    }

    /**
     * @param  User  $user
     * @param  EquipmentModel  $equipmentModel
     * @return bool
     */
    public function delete(User $user, EquipmentModel $equipmentModel): bool
    {
        if ($user->perm(Perm::EQUIPMENTS_CONFIG_DELETE_ALL)) {
            return true;
        }

        return $user->perm(Perm::EQUIPMENTS_CONFIG_DELETE_OWN) && Gate::allows('owner', $equipmentModel);
    }
}
