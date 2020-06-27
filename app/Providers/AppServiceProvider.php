<?php declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services
     *
     * @return void
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services
     *
     * @return void
     */
    public function boot(): void
    {
        \App\Models\User::observe(\App\Observers\UserObserver::class);
        \App\Models\Role::observe(\App\Observers\RoleObserver::class);
        \App\Models\Request::observe(\App\Observers\RequestObserver::class);
        \App\Models\RequestType::observe(\App\Observers\RequestTypeObserver::class);
        \App\Models\RequestStatus::observe(\App\Observers\RequestStatusObserver::class);
        \App\Models\RequestPriority::observe(\App\Observers\RequestPriorityObserver::class);
        \App\Models\Equipment::observe(\App\Observers\EquipmentObserver::class);
        \App\Models\EquipmentType::observe(\App\Observers\EquipmentTypeObserver::class);
        \App\Models\EquipmentModel::observe(\App\Observers\EquipmentModelObserver::class);
        \App\Models\EquipmentManufacturer::observe(\App\Observers\EquipmentManufacturerObserver::class);
    }
}
