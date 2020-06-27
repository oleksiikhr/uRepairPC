<?php declare(strict_types=1);

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
Route::group(['prefix' => 'settings', 'namespace' => 'Stat'], static function () {
    Route::get('global', 'GlobalController@index');
    Route::get('manifest', 'ManifestController@index');
});

/*
 * Section: Auth
 */
Route::group(['prefix' => 'auth'], static function () {
    Route::group(['middleware' => 'guest'], static function () {
        Route::post('login', 'AuthController@login');
        Route::post('refresh', 'AuthController@refresh');
    });

    Route::group(['middleware' => ['jwt.auth']], static function () {
        Route::get('profile', 'AuthController@profile');
        Route::post('logout', 'AuthController@logout');
    });
});

Route::group(['middleware' => ['jwt.auth']], static function () {
    Route::group(['prefix' => 'listeners'], static function () {
        Route::post('sync', 'ListenerController@sync');
    });

    /*
     * Section: Settings
     */
    Route::group(['prefix' => 'settings', 'namespace' => 'Stat'], static function () {
        Route::post('global', 'GlobalController@store');
        Route::post('manifest', 'ManifestController@store');
    });

    /*
     * Section: Users
     */
    Route::apiResource('users', 'UserController');
    Route::group(['prefix' => 'users'], static function () {
        Route::put('{user}/email', 'UserController@updateEmail');
        Route::put('{user}/password', 'UserController@updatePassword');
        Route::put('{user}/roles', 'UserController@updateRoles');
        Route::get('{user}/image', 'UserController@showImage');
        Route::post('{user}/image', 'UserController@updateImage');
        Route::delete('{user}/image', 'UserController@destroyImage');
    });

    /*
     * Section: Equipments
     */
    Route::apiResource('equipments', 'EquipmentController');
    Route::group(['prefix' => 'equipments'], static function () {
        Route::apiResource('manufacturers', 'EquipmentManufacturerController')->parameter('manufacturers', 'equipmentManufacturer');
        Route::apiResource('models', 'EquipmentModelController')->parameter('models', 'equipmentModel');
        Route::apiResource('types', 'EquipmentTypeController')->parameter('types', 'equipmentType');
        Route::apiResource('{equipment}/files', 'EquipmentFileController')->parameter('files', 'id');
    });

    /*
     * Section: Roles
     */
    Route::apiResource('roles', 'RoleController');
    Route::group(['prefix' => 'roles'], static function () {
        Route::put('{role}/permissions', 'RoleController@updatePermissions');
    });

    /*
     * Section: Permissions
     */
    Route::get('permissions', 'PermissionController@index');

    /*
     * Section: Requests
     */
    Route::apiResource('requests', 'RequestController');
    Route::group(['prefix' => 'requests'], static function () {
        Route::apiResource('statuses', 'RequestStatusController')->parameter('statuses', 'requestStatus');
        Route::apiResource('priorities', 'RequestPriorityController')->parameter('priorities', 'requestPriority');
        Route::apiResource('types', 'RequestTypeController')->parameter('types', 'requestType');
        Route::apiResource('{request}/comments', 'RequestCommentController')->parameter('comments', 'id');
        Route::apiResource('{request}/files', 'RequestFileController')->parameter('files', 'id');
    });
});
