<?php declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * @inheritDoc
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * The path to the "home" route for your application
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * @inheritDoc
     */
    public function boot(): void
    {
        Route::pattern('id', '[0-9]+');

        Route::pattern('user', '[0-9]+');
        Route::model('user', \App\Models\User::class);

        Route::pattern('role', '[0-9]+');
        Route::model('role', \App\Models\Role::class);

        Route::pattern('file', '[0-9]+');
        Route::model('file', \App\Models\File::class);

        Route::pattern('equipment', '[0-9]+');
        Route::bind('equipment', static fn ($id) => \App\Models\Equipment::querySelectJoins()->firstOrFail($id));
        Route::pattern('equipmentManufacturer', '[0-9]+');
        Route::model('equipmentManufacturer', \App\Models\EquipmentManufacturer::class);
        Route::pattern('equipmentModel', '[0-9]+');
        Route::bind('equipmentModel', static fn ($id) => \App\Models\EquipmentModel::querySelectJoins()->firstOrFail($id));
        Route::pattern('equipmentType', '[0-9]+');
        Route::model('equipmentType', \App\Models\EquipmentType::class);

        Route::pattern('request', '[0-9]+');
        Route::bind('request', static fn ($id) => \App\Models\Request::querySelectJoins()->firstOrFail($id));
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
     * Define the routes for the application
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
     * Define the "web" routes for the application
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
     * Define the "api" routes for the application
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
}
