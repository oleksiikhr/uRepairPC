<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\Perm;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PermissionController extends Controller
{
    /**
     * Add middleware depends on user permissions.
     *
     * @param  Request  $request
     * @return array
     */
    public function permissions(Request $request): array
    {
        return [
            'index' => Perm::ROLES_VIEW_ALL,
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $permissions = (object) Perm::getStructure();

        return response()->json($permissions);
    }
}
