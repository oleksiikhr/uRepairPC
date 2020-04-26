<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\User;
use App\Equipment;
use App\Enums\Perm;
use Illuminate\Http\Request;
use App\Realtime\Equipments\EJoin;
use Illuminate\Http\JsonResponse;
use App\Http\Helpers\FilesHelper;
use App\Realtime\Equipments\ECreate;
use App\Realtime\Equipments\EUpdate;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\EquipmentRequest;

class EquipmentController extends Controller
{
    /**
     * @var User
     */
    private $user;

    /**
     * Add middleware depends on user permissions.
     *
     * @param  Request  $request
     * @return array
     */
    public function permissions(Request $request): array
    {
        $this->user = auth()->user();

        return [
            'index' => [Perm::EQUIPMENTS_VIEW_ALL, Perm::EQUIPMENTS_VIEW_OWN],
            'show' => [Perm::EQUIPMENTS_VIEW_ALL, Perm::EQUIPMENTS_VIEW_OWN],
            'store' => Perm::EQUIPMENTS_CREATE,
            'update' => [Perm::EQUIPMENTS_EDIT_ALL, Perm::EQUIPMENTS_EDIT_OWN],
            'destroy' => [Perm::EQUIPMENTS_DELETE_ALL, Perm::EQUIPMENTS_DELETE_OWN],
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @param  EquipmentRequest  $request
     * @return JsonResponse
     */
    public function index(EquipmentRequest $request): JsonResponse
    {
        $query = Equipment::querySelectJoins();

        // Search
        if ($request->has('search') && $request->has('columns') && ! empty($request->columns)) {
            foreach ($request->columns as $column) {
                $query->orWhere(Equipment::SEARCH_RELATIONSHIP[$column] ?? $column, 'LIKE', '%'.$request->search.'%');
            }
        }

        // Order
        if ($request->has('sortColumn')) {
            $query->orderBy($request->sortColumn, $request->sortOrder === 'descending' ? 'desc' : 'asc');
        }

        // Show only own equipments
        if (! $this->user->perm(Perm::EQUIPMENTS_VIEW_ALL)) {
            $query->where('user_id', $this->user->id);
        }

        $list = $query->paginate(self::PAGINATE_DEFAULT);
        EJoin::dispatchAfterResponse(...$list->items());

        return response()->json($list);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  EquipmentRequest  $request
     * @return JsonResponse
     */
    public function store(EquipmentRequest $request): JsonResponse
    {
        $equipment = new Equipment;
        $equipment->fill($request->all());
        $equipment->user_id = auth()->id();

        if (! $equipment->save()) {
            return response()->json(['message' => __('app.database.save_error')], 422);
        }

        $equipment = Equipment::querySelectJoins()->findOrFail($equipment->id);
        ECreate::dispatchAfterResponse($equipment);

        return response()->json([
            'message' => __('app.equipments.store'),
            'equipment' => $equipment,
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
        $equipment = Equipment::querySelectJoins()->findOrFail($id);

        // Show only own equipments
        if (! $this->user->perm(Perm::EQUIPMENTS_VIEW_ALL) && Gate::denies('owner', $equipment)) {
            return $this->responseNoPermission();
        }

        EJoin::dispatchAfterResponse($equipment);

        return response()->json([
            'message' => __('app.equipments.show'),
            'equipment' => $equipment,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  EquipmentRequest  $request
     * @param  int  $id
     * @return JsonResponse
     */
    public function update(EquipmentRequest $request, int $id): JsonResponse
    {
        $equipment = Equipment::findOrFail($id);

        // Edit only own equipments
        if (! $this->user->perm(Perm::EQUIPMENTS_EDIT_ALL) && Gate::denies('owner', $equipment)) {
            return $this->responseNoPermission();
        }

        $equipment->fill($request->all());

        if (! $equipment->save()) {
            return $this->responseDatabaseSaveError();
        }

        // Update model new data from relationship
        $equipment = Equipment::querySelectJoins()->findOrFail($equipment->id);
        EUpdate::dispatchAfterResponse($equipment->id, $equipment);

        return response()->json([
            'message' => __('app.equipments.update'),
            'equipment' => $equipment,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  EquipmentRequest  $request
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy(EquipmentRequest $request, int $id): JsonResponse
    {
        $equipment = Equipment::findOrFail($id);

        // Delete only own equipments
        if (! $this->user->perm(Perm::EQUIPMENTS_DELETE_ALL) && Gate::denies('owner', $equipment)) {
            return $this->responseNoPermission();
        }

        if ($request->files_delete) {
            if (! FilesHelper::delete($equipment->files)) {
                return response()->json(['message' => __('app.files.files_not_deleted')], 422);
            }
        }

        if (! $equipment->delete()) {
            return $this->responseDatabaseDestroyError();
        }

        return response()->json([
            'message' => __('app.equipments.destroy'),
        ]);
    }
}
