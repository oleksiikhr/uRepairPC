<?php

namespace App\Http\Middleware;

use Closure;

class Permission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string $permission
     * @return mixed
     * @throws \Exception
     */
    public function handle($request, Closure $next, $permission)
    {
        $user = auth()->user();

        if (! $user) {
            return response()->json(['message' => __('app.middleware.no_permission')], 422);
        }

        $permissions = is_array($permission) ? $permission : explode('|', $permission);

        if ($user->perm($permissions)) {
            return $next($request);
        }

        return response()->json(['message' => __('app.middleware.no_permission')], 422);
    }
}
