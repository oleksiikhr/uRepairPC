<?php

namespace App\Observers;

use App\Role;
use App\Events\Roles\EDelete;

class RoleObserver
{
    /**
     * Handle the role "deleted" event.
     *
     * @param  \App\Role  $role
     * @return void
     */
    public function deleted(Role $role)
    {
        event(new EDelete($role));
    }
}
