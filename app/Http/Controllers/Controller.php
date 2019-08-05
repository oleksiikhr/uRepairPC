<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Interfaces\IPermissions;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

abstract class Controller extends BaseController implements IPermissions
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /** @var int */
    const PAGINATE_DEFAULT = 25;

    public function __construct(Request $request)
    {
        $this->allowPermissions($this->permissions($request));
    }

    /**
     * Register middleware on depends key-value array.
     *  key - method
     *  value - list of permissions.
     *
     * @param  array  $methods
     * @return void
     */
    private function allowPermissions(array $methods): void
    {
        if (empty($methods)) {
            return;
        }

        $activeRoute = Route::getCurrentRoute();

        if (! $activeRoute) {
            return;
        }

        $activeMethod = $activeRoute->getActionMethod();

        if (array_key_exists($activeMethod, $methods)) {
            $role = $methods[$activeMethod];
            $permissions = is_array($role) ? implode('|', $role) : $role;
            if (! empty($permissions)) {
                $this->middleware('permission:'.$permissions);
            }
        }
    }

    /**
     * @return JsonResponse
     */
    public function responseNoPermission(): JsonResponse
    {
        return response()->json(['message' => __('app.middleware.no_permission')], 422);
    }

    /**
     * @return JsonResponse
     */
    public function responseDatabaseSaveError(): JsonResponse
    {
        return response()->json(['message' => __('app.database.save_error')], 422);
    }

    /**
     * @return JsonResponse
     */
    public function responseDatabaseDestroyError(): JsonResponse
    {
        return response()->json(['message' => __('app.database.destroy_error')], 422);
    }
}
