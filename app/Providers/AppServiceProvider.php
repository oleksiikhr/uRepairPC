<?php

namespace App\Providers;

use App\Role;
use App\User;
use App\Request;
use App\Equipment;
use App\Http\Kernel;
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
use Illuminate\Support\Facades\Schema;
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
        Schema::defaultStringLength(191);
    }

    /**
     * Bootstrap any application services.
     *
     * @param  Kernel  $kernel
     * @return void
     */
    public function boot(Kernel $kernel)
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

//        \Illuminate\Support\Facades\DB::listen(function ($query) {
//            var_dump($query->sql);
//        });
    }
}
