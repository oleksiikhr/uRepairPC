<?php

declare(strict_types=1);

use App\Http\Controllers as File;
use Illuminate\Support\Facades\Route;

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
    Route::get('global', [File\Stat\GlobalController::class, 'index']);
    Route::get('manifest', [File\Stat\ManifestController::class, 'index']);
});

/*
 * Section: Auth
 */
Route::group(['prefix' => 'auth'], static function () {
    Route::group(['middleware' => 'guest'], static function () {
        Route::post('login', [File\AuthController::class, 'login']);
        Route::post('refresh', [File\AuthController::class, 'refresh']);
    });

    Route::group(['middleware' => ['jwt.auth']], static function () {
        Route::get('profile', [File\AuthController::class, 'profile']);
        Route::post('logout', [File\AuthController::class, 'logout']);
    });
});

Route::group(['middleware' => ['jwt.auth']], static function () {
    Route::group(['prefix' => 'listeners'], static function () {
        Route::post('sync', [File\ListenerController::class, 'sync']);
    });

    /*
     * Section: Settings
     */
    Route::group(['prefix' => 'settings', 'namespace' => 'Stat'], static function () {
        Route::post('global', [File\Stat\GlobalController::class, 'store']);
        Route::post('manifest', [File\Stat\ManifestController::class, 'store']);
    });

    /*
     * Section: Users
     */
    Route::apiResource('users', File\UserController::class);
    Route::group(['prefix' => 'users'], static function () {
        Route::put('{user}/email', [File\UserController::class, 'updateEmail']);
        Route::put('{user}/password', [File\UserController::class, 'updatePassword']);
        Route::put('{user}/roles', [File\UserController::class, 'updateRoles']);
        Route::get('{user}/image', [File\UserController::class, 'showImage']);
        Route::post('{user}/image', [File\UserController::class, 'updateImage']);
        Route::delete('{user}/image', [File\UserController::class, 'destroyImage']);
    });

    /*
     * Section: Equipments
     */
    Route::apiResource('equipments', File\EquipmentController::class);
    Route::group(['prefix' => 'equipments'], static function () {
        Route::apiResource('manufacturers', File\EquipmentManufacturerController::class)->parameter('manufacturers', 'equipmentManufacturer');
        Route::apiResource('models', File\EquipmentModelController::class)->parameter('models', 'equipmentModel');
        Route::apiResource('types', File\EquipmentTypeController::class)->parameter('types', 'equipmentType');
        Route::apiResource('{equipment}/files', File\EquipmentFileController::class)->parameter('files', 'id');
    });

    /*
     * Section: Roles
     */
    Route::apiResource('roles', File\RoleController::class);
    Route::group(['prefix' => 'roles'], static function () {
        Route::put('{role}/permissions', [File\RoleController::class, 'updatePermissions']);
    });

    /*
     * Section: Permissions
     */
    Route::get('permissions', [File\PermissionController::class, 'index']);

    /*
     * Section: Requests
     */
    Route::apiResource('requests', File\RequestController::class);
    Route::group(['prefix' => 'requests'], static function () {
        Route::apiResource('statuses', File\RequestStatusController::class)->parameter('statuses', 'requestStatus');
        Route::apiResource('priorities', File\RequestPriorityController::class)->parameter('priorities', 'requestPriority');
        Route::apiResource('types', File\RequestTypeController::class)->parameter('types', 'requestType');
        Route::apiResource('{request}/comments', File\RequestCommentController::class)->parameter('comments', 'id');
        Route::apiResource('{request}/files', File\RequestFileController::class)->parameter('files', 'id');
    });
});
