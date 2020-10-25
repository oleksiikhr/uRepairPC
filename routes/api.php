<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Http\Controllers as Controller;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*
 * Section: Settings
 */
Route::group(['prefix' => 'settings'], static function () {
    Route::get('global', [Controller\Stat\GlobalController::class, 'index']);
    Route::get('manifest', [Controller\Stat\ManifestController::class, 'index']);
});

/*
 * Section: Auth
 */
Route::group(['prefix' => 'auth'], static function () {
    Route::group(['middleware' => 'guest'], static function () {
        Route::post('login', [Controller\AuthController::class, 'login']);
        Route::post('refresh', [Controller\AuthController::class, 'refresh']);
    });

    Route::group(['middleware' => ['jwt.auth']], static function () {
        Route::get('profile', [Controller\AuthController::class, 'profile']);
        Route::post('logout', [Controller\AuthController::class, 'logout']);
    });
});

Route::group(['middleware' => ['jwt.auth']], static function () {
    Route::group(['prefix' => 'listeners'], static function () {
        Route::post('sync', [Controller\ListenerController::class, 'sync']);
    });

    /*
     * Section: Settings
     */
    Route::group(['prefix' => 'settings', 'namespace' => 'Stat'], static function () {
        Route::post('global', [Controller\Stat\GlobalController::class, 'store']);
        Route::post('manifest', [Controller\Stat\ManifestController::class, 'store']);
    });

    /*
     * Section: Users
     */
    Route::apiResource('users', Controller\UserController::class);
    Route::group(['prefix' => 'users'], static function () {
        Route::put('{user}/email', [Controller\UserController::class, 'updateEmail']);
        Route::put('{user}/password', [Controller\UserController::class, 'updatePassword']);
        Route::put('{user}/roles', [Controller\UserController::class, 'updateRoles']);
        Route::get('{user}/image', [Controller\UserController::class, 'showImage']);
        Route::post('{user}/image', [Controller\UserController::class, 'updateImage']);
        Route::delete('{user}/image', [Controller\UserController::class, 'destroyImage']);
    });

    /*
     * Section: Equipments
     */
    Route::apiResource('equipments', Controller\EquipmentController::class);
    Route::group(['prefix' => 'equipments'], static function () {
        Route::apiResource('manufacturers', Controller\EquipmentManufacturerController::class)->parameter('manufacturers', 'equipmentManufacturer');
        Route::apiResource('models', Controller\EquipmentModelController::class)->parameter('models', 'equipmentModel');
        Route::apiResource('types', Controller\EquipmentTypeController::class)->parameter('types', 'equipmentType');
        Route::apiResource('{equipment}/files', Controller\EquipmentFileController::class)->parameter('files', 'id');
    });

    /*
     * Section: Roles
     */
    Route::apiResource('roles', Controller\RoleController::class);
    Route::group(['prefix' => 'roles'], static function () {
        Route::put('{role}/permissions', [Controller\RoleController::class, 'updatePermissions']);
    });

    /*
     * Section: Permissions
     */
    Route::get('permissions', [Controller\PermissionController::class, 'index']);

    /*
     * Section: Requests
     */
    Route::apiResource('requests', Controller\RequestController::class);
    Route::group(['prefix' => 'requests'], static function () {
        Route::apiResource('statuses', Controller\RequestStatusController::class)->parameter('statuses', 'requestStatus');
        Route::apiResource('priorities', Controller\RequestPriorityController::class)->parameter('priorities', 'requestPriority');
        Route::apiResource('types', Controller\RequestTypeController::class)->parameter('types', 'requestType');
        Route::apiResource('{request}/comments', Controller\RequestCommentController::class)->parameter('comments', 'id');
        Route::apiResource('{request}/files', Controller\RequestFileController::class)->parameter('files', 'id');
    });

    /*
     * Section: Jobs
     */
    Route::apiResource('jobs', Controller\JobController::class)->only(['index', 'show', 'destroy']);
    Route::group(['prefix' => 'jobs'], static function () {
        Route::delete('destroy-all', [Controller\JobController::class, 'destroyAll']);
        Route::apiResource('failed', Controller\FailedJobController::class)->parameter('failed', 'failedJob')->only(['index', 'show', 'destroy']);
        Route::post('failed/retry', [Controller\FailedJobController::class, 'retry']);
        Route::delete('failed/destroy-all', [Controller\FailedJobController::class, 'destroyAll']);
    });
});
