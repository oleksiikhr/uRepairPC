<?php declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * @inheritDoc
     */
    protected $policies = [
        \App\Models\EquipmentManufacturer::class => \App\Policies\EquipmentManufacturerPolicy::class,
        \App\Models\EquipmentModel::class => \App\Policies\EquipmentModelPolicy::class,
        \App\Models\Equipment::class => \App\Policies\EquipmentPolicy::class,
        \App\Models\EquipmentType::class => \App\Policies\EquipmentTypePolicy::class,
        \App\Models\File::class => \App\Policies\FilePolicy::class,
        \App\Models\RequestComment::class => \App\Policies\RequestCommentPolicy::class,
        \App\Models\Request::class => \App\Policies\RequestPolicy::class,
        \App\Models\RequestPriority::class => \App\Policies\RequestPriorityPolicy::class,
        \App\Models\RequestStatus::class => \App\Policies\RequestStatusPolicy::class,
        \App\Models\RequestType::class => \App\Policies\RequestTypePolicy::class,
        \App\Models\Role::class => \App\Policies\RolePolicy::class,
        \App\Models\User::class => \App\Policies\UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services
     *
     * @return void
     */
    public function boot(): void
    {
        $this->registerPolicies();

        Gate::define('owner', static fn ($user, $model) => $user->id === $model->user_id);
        Gate::define('assign', static fn ($user, $model) => $user->id === $model->assign_id);
    }
}
