<?php

declare(strict_types=1);

namespace App\Console\Commands\Permissions;

use App\Enums\Perm;
use App\Models\Role;
use App\Models\RolePermission;
use Illuminate\Console\Command;

class RefreshPermissionsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permissions:refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update permissions after change';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $permissions = Perm::getAll();

        foreach ($permissions as $permission) {
            RolePermission::updateOrCreate(
                ['role_id' => 1, 'name' => $permission],
                ['name' => $permission]
            );
        }

        return 0;
    }
}
