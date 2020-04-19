<?php declare(strict_types=1);

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\File;

Route::get('manifest.json', 'Stat\ManifestController@index');

Route::get('{any}', static function () {
    $path = public_path('index.html');

    if (file_exists($path)) {
        return File::get($path);
    }

    return response()->json(['message' => '/api'], 422);
})->where('any', '.*');
