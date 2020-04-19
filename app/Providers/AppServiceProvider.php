<?php declare(strict_types=1);

namespace App\Providers;

use App\Role;
use App\User;
use App\Request;
use App\Equipment;
use App\RequestType;
use App\EquipmentType;
use App\RequestStatus;
use App\EquipmentModel;
use App\RequestPriority;
use App\EquipmentManufacturer;
use App\Observers\RoleObserver;
use App\Observers\UserObserver;
use App\Observers\RequestObserver;
use App\Observers\EquipmentObserver;
use App\Observers\RequestTypeObserver;
use Illuminate\Support\ServiceProvider;
use App\Observers\EquipmentTypeObserver;
use App\Observers\RequestStatusObserver;
use App\Observers\EquipmentModelObserver;
use App\Observers\RequestPriorityObserver;
use App\Observers\EquipmentManufacturerObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        User::observe(UserObserver::class);
        Role::observe(RoleObserver::class);
        Equipment::observe(EquipmentObserver::class);
        EquipmentType::observe(EquipmentTypeObserver::class);
        EquipmentModel::observe(EquipmentModelObserver::class);
        EquipmentManufacturer::observe(EquipmentManufacturerObserver::class);
        Request::observe(RequestObserver::class);
        RequestType::observe(RequestTypeObserver::class);
        RequestPriority::observe(RequestPriorityObserver::class);
        RequestStatus::observe(RequestStatusObserver::class);
    }
}
