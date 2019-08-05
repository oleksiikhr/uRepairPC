<?php

namespace App\Http\Controllers;

use App\Enums\Perm;
use Illuminate\Http\Request;

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
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json(Perm::getStructure());
    }
}
