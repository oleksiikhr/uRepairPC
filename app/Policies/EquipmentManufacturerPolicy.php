<?php declare(strict_types=1);

namespace App\Policies;

use App\Enums\Perm;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use App\Models\EquipmentManufacturer;

class EquipmentManufacturerPolicy
{
    /**
     * @param  User  $user
     * @param  EquipmentManufacturer  $equipmentManufacturer
     * @return bool
     */
    public function update(User $user, EquipmentManufacturer $equipmentManufacturer): bool
    {
        if ($user->perm(Perm::EQUIPMENTS_CONFIG_EDIT_ALL)) {
            return true;
        }

        return $user->perm(Perm::EQUIPMENTS_CONFIG_EDIT_OWN) && Gate::allows('owner', $equipmentManufacturer);
    }

    /**
     * @param  User  $user
     * @param  EquipmentManufacturer  $equipmentManufacturer
     * @return bool
     */
    public function delete(User $user, EquipmentManufacturer $equipmentManufacturer): bool
    {
        if ($user->perm(Perm::EQUIPMENTS_CONFIG_DELETE_ALL)) {
            return true;
        }

        return $user->perm(Perm::EQUIPMENTS_CONFIG_DELETE_OWN) && Gate::allows('owner', $equipmentManufacturer);
    }
}
