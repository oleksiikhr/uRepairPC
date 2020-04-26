<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Role;
use App\Enums\Perm;
use App\Realtime\Roles\EJoin;
use Illuminate\Http\Request;
use App\Realtime\Roles\ECreate;
use App\Realtime\Roles\EUpdate;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\RoleRequest;

class RoleController extends Controller
{
    /**
     * Add middleware depends on user permissions.
     *
     * @param  Request  $request
     * @return array
     */
    public function permissions(Request $request): array
    {
        $requestId = (int) $request->route('role');

        return [
            'index' => Perm::ROLES_VIEW_ALL,
            'show' => Perm::ROLES_VIEW_ALL,
            'store' => Perm::ROLES_EDIT_ALL,
            'update' => Perm::ROLES_EDIT_ALL,
            'destroy' => $requestId === 1 ? Perm::DISABLE : Perm::ROLES_EDIT_ALL,
            'updatePermissions' => $requestId === 1 ? Perm::DISABLE : Perm::ROLES_EDIT_ALL,
        ];
    }

    /**
     * Display a listing of the resource.
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
        if ($request->has('search') && $request->has('columns') && ! empty($request->columns)) {
            foreach ($request->columns as $column) {
                $query->orWhere($column, 'LIKE', '%'.$request->search.'%');
            }
        }

        // Order
        if ($request->has('sortColumn')) {
            $query->orderBy($request->sortColumn, $request->sortOrder === 'descending' ? 'desc' : 'asc');
        }

        $list = $query->paginate($request->count ?? self::PAGINATE_DEFAULT);
        EJoin::dispatchAfterResponse(...$list->items());

        return response()->json($list);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  RoleRequest  $request
     * @return JsonResponse
     */
    public function store(RoleRequest $request): JsonResponse
    {
        $role = new Role;
        $role->fill($request->all());

        if (! $role->save()) {
            return $this->responseDatabaseSaveError();
        }

        ECreate::dispatchAfterResponse($role);

        return response()->json([
            'message' => __('app.roles.store'),
            'role' => $role,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $role = Role::with('permissions')->findOrFail($id);

        EJoin::dispatchAfterResponse($role);

        return response()->json([
            'message' => __('app.roles.show'),
            'role' => $role,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  RoleRequest  $request
     * @param  int  $id
     * @return JsonResponse
     */
    public function update(RoleRequest $request, int $id): JsonResponse
    {
        $role = Role::findOrFail($id);
        $role->fill($request->all());

        if (! $role->save()) {
            return $this->responseDatabaseSaveError();
        }

        EUpdate::dispatchAfterResponse($role->id, $role);

        return response()->json([
            'message' => __('app.roles.update'),
            'role' => $role,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return JsonResponse
     */
    public function updatePermissions(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'permissions' => 'array',
            'permissions.*' => 'string',
        ]);

        $role = Role::findOrFail($id);
        $role->syncPermissions($request->permissions);

        EUpdate::dispatchAfterResponse($id, $role);

        return response()->json([
            'message' => __('app.roles.update_permissions'),
            'role' => $role,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $role = Role::findOrFail($id);

        if (! $role->delete()) {
            return $this->responseDatabaseDestroyError();
        }

        return response()->json([
            'message' => __('app.roles.destroy'),
        ]);
    }
}
