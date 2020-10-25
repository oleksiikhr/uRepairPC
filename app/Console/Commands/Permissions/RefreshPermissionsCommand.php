<?php

declare(strict_types=1);

namespace App\Console\Commands\Permissions;

use App\Enums\Perm;
use App\Models\Role;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

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
        $role = Role::query()->firstOrFail();
        $permissions = Perm::getAll();

        $role->syncPermissions($permissions);

        Cache::tags(Role::PERMISSION_TAGS)->flush();

        return 0;
    }
}
