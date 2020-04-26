<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\AuthRequest;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    /**
     * Add middleware depends on user permissions.
     *
     * @param  Request  $request
     * @return array
     */
    public function permissions(Request $request): array
    {
        return [];
    }

    /**
     * Get a current user with permissions.
     *
     * @return JsonResponse
     */
    public function profile(): JsonResponse
    {
        $user = auth()->user();

        return response()->json([
            'user' => $user,
            'permissions' => $user->getAllPermNames(),
        ]);
    }

    /**
     * Auth the user by login and password.
     *
     * @param  AuthRequest  $request
     * @return JsonResponse
     */
    public function login(AuthRequest $request): JsonResponse
    {
        if (! $token = JWTAuth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => __('app.auth.login_error')], 422);
        }

        $user = auth()->user();

        return response()->json([
            'message' => __('app.auth.login_success'),
            'token' => $token,
            'user' => $user,
            'permissions' => $user->getAllPermNames(),
        ]);
    }

    /**
     * Refresh token to user.
     *
     * @return JsonResponse
     */
    public function refresh(): JsonResponse
    {
        $token = JWTAuth::getToken();

        if (! $token) {
            return response()->json(['message' => __('app.auth.token_invalid')], 422);
        }

        $newToken = JWTAuth::refresh($token);

        // deleted_at has value - user is empty
        $userId = JWTAuth::setToken($newToken)->getPayload()->get('sub');
        $user = User::find($userId);

        if (! $user) {
            return response()->json(['message' => __('app.auth.token_invalid')], 422);
        }

        return response()->json([
            'message' => __('app.auth.token_refresh'),
            'token' => $newToken,
        ]);
    }

    /**
     * Invalidate current JWT Token.
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        JWTAuth::invalidate(JWTAuth::getToken());

        return response()->json([
            'message' => __('app.auth.logout'),
        ]);
    }
}
