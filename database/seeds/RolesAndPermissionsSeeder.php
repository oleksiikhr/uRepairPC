<?php

use App\Role;
use App\Enums\Perm;
use App\RolePermission;
use Illuminate\Database\Seeder;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*
         * ADMIN
         */
        $adminRole = Role::create([
            'name' => __('perm.roles.admins'),
            'color' => '#f56c6c',
        ]);

        $adminPermissions = Perm::getAll();

        foreach ($adminPermissions as $permission) {
            RolePermission::create([
                'role_id' => $adminRole->id,
                'name' => $permission,
            ]);
        }

        /*
         * USER
         */
        $userRole = Role::create([
            'name' => __('perm.roles.users'),
            'color' => '#67c23a',
            'default' => true,
        ]);

        $userPermissions = [
            Perm::PROFILE_EDIT, // profile
            Perm::EQUIPMENTS_VIEW_OWN, // equipments
            Perm::EQUIPMENTS_CONFIG_VIEW_ALL,
            Perm::REQUESTS_VIEW_SECTION, // requests
            Perm::REQUESTS_VIEW_OWN,
            Perm::REQUESTS_EDIT_OWN,
            Perm::REQUESTS_CREATE,
            Perm::REQUESTS_FILES_VIEW_OWN,
            Perm::REQUESTS_FILES_EDIT_OWN,
            Perm::REQUESTS_FILES_CREATE,
            Perm::REQUESTS_COMMENTS_VIEW_ALL,
            Perm::REQUESTS_COMMENTS_CREATE,
            Perm::REQUESTS_COMMENTS_EDIT_OWN,
            Perm::REQUESTS_CONFIG_VIEW_ALL,
        ];

        foreach ($userPermissions as $permission) {
            RolePermission::create([
                'role_id' => $userRole->id,
                'name' => $permission,
            ]);
        }
    }
}
