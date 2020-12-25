<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * {@inheritdoc}
     */
    public function boot(): void
    {
        $this->configureRateLimiting();

        Route::pattern('id', '[0-9]+');

        Route::pattern('user', '[0-9]+');
        Route::model('user', \App\Models\User::class);

        Route::pattern('role', '[0-9]+');
        Route::model('role', \App\Models\Role::class);

        Route::pattern('file', '[0-9]+');
        Route::model('file', \App\Models\File::class);

        Route::pattern('equipment', '[0-9]+');
        Route::bind('equipment', static fn (int $id) => \App\Models\Equipment::querySelectJoins()->findOrFail($id));
        Route::pattern('equipmentManufacturer', '[0-9]+');
        Route::model('equipmentManufacturer', \App\Models\EquipmentManufacturer::class);
        Route::pattern('equipmentModel', '[0-9]+');
        Route::bind('equipmentModel', static fn (int $id) => \App\Models\EquipmentModel::querySelectJoins()->findOrFail($id));
        Route::pattern('equipmentType', '[0-9]+');
        Route::model('equipmentType', \App\Models\EquipmentType::class);

        Route::pattern('job', '[0-9]+');
        Route::model('job', \App\Models\Job::class);
        Route::pattern('failedJob', '[0-9]+');
        Route::model('failedJob', \App\Models\FailedJob::class);

        Route::pattern('request', '[0-9]+');
        Route::bind('request', static fn (int $id) => \App\Models\Request::querySelectJoins()->findOrFail($id));
        Route::pattern('requestPriority', '[0-9]+');
        Route::model('requestPriority', \App\Models\RequestPriority::class);
        Route::pattern('requestStatus', '[0-9]+');
        Route::model('requestStatus', \App\Models\RequestType::class);
        Route::pattern('requestType', '[0-9]+');
        Route::model('requestType', \App\Models\RequestType::class);
        Route::pattern('requestComment', '[0-9]+');
        Route::model('requestComment', \App\Models\RequestComment::class);

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map(): void
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes(): void
    {
        Route::namespace($this->namespace)
            ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless
     *
     * @return void
     */
    protected function mapApiRoutes(): void
    {
        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api.php'));
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api', static function (Request $request) {
            return Limit::perMinute(100);
        });
    }
}
