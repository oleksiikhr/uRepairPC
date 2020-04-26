<?php declare(strict_types=1);

namespace App\Observers;

use App\Role;
use App\Realtime\Roles\EDelete;

class RoleObserver
{
    /**
     * Handle the role "deleted" event.
     *
     * @param  Role  $role
     * @return void
     */
    public function deleted(Role $role): void
    {
        EDelete::dispatchAfterResponse($role);
    }
}
