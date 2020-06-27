<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\Perm;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Realtime\Roles\EJoin;
use App\Realtime\Roles\ECreate;
use App\Realtime\Roles\EUpdate;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\RoleRequest;
use Illuminate\Auth\Access\AuthorizationException;

class RoleController extends Controller
{
    /**
     * @inheritDoc
     */
    public function permissions(): array
    {
        return [
            'index' => Perm::ROLES_VIEW_ALL,
            'show' => Perm::ROLES_VIEW_ALL,
            'store' => Perm::ROLES_EDIT_ALL,
            'update' => Perm::ROLES_EDIT_ALL,
            'destroy' => Perm::ROLES_EDIT_ALL,
            'updatePermissions' => Perm::ROLES_EDIT_ALL,
        ];
    }

    /**
     * Display a listing of the resource
     *
     * @param  RoleRequest  $request
     * @return JsonResponse
     */
    public function index(RoleRequest $request): JsonResponse
    {
        $query = Role::query();

        if ($request->permissions) {
            $query->with('permissions');
        }

        // Search
        if ($request->has('search') && $request->exists('columns')) {
            foreach ($request->columns as $column) {
                $query->orWhere($column, 'LIKE', $request->search.'%');
            }
        }

        // Order
        if ($request->has('sortColumn')) {
            $query->orderBy($request->sortColumn, $request->sortOrder === 'descending' ? 'desc' : 'asc');
        }

        $list = $query->paginate($request->count);
        EJoin::dispatchAfterResponse(...$list->items());

        return response()->json($list);
    }

    /**
     * Store a newly created resource in storage
     *
     * @param  RoleRequest  $request
     * @return JsonResponse
     */
    public function store(RoleRequest $request): JsonResponse
    {
        $role = new Role($request->validated());
        $role->save();

        ECreate::dispatchAfterResponse($role);

        return response()->json([
            'message' => __('app.roles.store'),
            'role' => $role,
        ]);
    }

    /**
     * Display the specified resource
     *
     * @param  Role  $role
     * @return JsonResponse
     */
    public function show(Role $role): JsonResponse
    {
        $role->load('permissions');

        EJoin::dispatchAfterResponse($role);

        return response()->json([
            'message' => __('app.roles.show'),
            'role' => $role,
        ]);
    }

    /**
     * Update the specified resource in storage
     *
     * @param  RoleRequest  $request
     * @param  Role  $role
     * @return JsonResponse
     */
    public function update(RoleRequest $request, Role $role): JsonResponse
    {
        $role->fill($request->validated());
        $role->save();

        EUpdate::dispatchAfterResponse($role->id, $role);

        return response()->json([
            'message' => __('app.roles.update'),
            'role' => $role,
        ]);
    }

    /**
     * Update the specified resource in storage
     *
     * @param  Request  $request
     * @param  Role  $role
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function updatePermissions(Request $request, Role $role): JsonResponse
    {
        $this->authorize('updatePermissions', $role);

        // TODO Request class
        $request->validate([
            'permissions' => 'array',
            'permissions.*' => 'string',
        ]);

        $role->syncPermissions($request->permissions);

        EUpdate::dispatchAfterResponse($role->id, $role);

        return response()->json([
            'message' => __('app.roles.update_permissions'),
            'role' => $role,
        ]);
    }

    /**
     * Remove the specified resource from storage
     *
     * @param  Role  $role
     * @return JsonResponse
     * @throws AuthorizationException
     * @throws \Exception
     */
    public function destroy(Role $role): JsonResponse
    {
        $this->authorize('delete', $role);

        $role->delete();

        return response()->json([
            'message' => __('app.roles.destroy'),
        ]);
    }
}
