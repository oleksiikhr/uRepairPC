<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\User;
use App\Enums\Perm;
use App\EquipmentType;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Realtime\EquipmentTypes\EJoin;
use Illuminate\Support\Facades\Gate;
use App\Realtime\EquipmentTypes\ECreate;
use App\Realtime\EquipmentTypes\EUpdate;
use App\Http\Requests\EquipmentTypeRequest;

class EquipmentTypeController extends Controller
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
            'index' => Perm::EQUIPMENTS_CONFIG_VIEW_ALL,
            'show' => Perm::EQUIPMENTS_CONFIG_VIEW_ALL,
            'store' => Perm::EQUIPMENTS_CONFIG_CREATE,
            'update' => [Perm::EQUIPMENTS_CONFIG_EDIT_OWN, Perm::EQUIPMENTS_CONFIG_EDIT_ALL],
            'destroy' => [Perm::EQUIPMENTS_CONFIG_DELETE_OWN, Perm::EQUIPMENTS_CONFIG_DELETE_ALL],
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $list = EquipmentType::all();

        EJoin::dispatchAfterResponse(...$list);

        return response()->json($list);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  EquipmentTypeRequest  $request
     * @return JsonResponse
     */
    public function store(EquipmentTypeRequest $request): JsonResponse
    {
        $equipmentType = new EquipmentType;
        $equipmentType->fill($request->all());
        $equipmentType->user_id = $this->user->id;

        if (! $equipmentType->save()) {
            return $this->responseDatabaseSaveError();
        }

        ECreate::dispatchAfterResponse($equipmentType);

        return response()->json([
            'message' => __('app.equipment_type.store'),
            'equipment_type' => $equipmentType,
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
        $equipmentType = EquipmentType::findOrFail($id);

        EJoin::dispatchAfterResponse($equipmentType);

        return response()->json([
            'message' => __('app.equipment_type.show'),
            'equipment_type' => $equipmentType,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  EquipmentTypeRequest  $request
     * @param  int  $id
     * @return JsonResponse
     */
    public function update(EquipmentTypeRequest $request, int $id): JsonResponse
    {
        $equipmentType = EquipmentType::findOrFail($id);

        // Edit only own equipments config
        if (! $this->user->perm(Perm::EQUIPMENTS_CONFIG_EDIT_ALL) &&
            Gate::denies('owner', $equipmentType)
        ) {
            return $this->responseNoPermission();
        }

        $equipmentType->fill($request->all());

        if (! $equipmentType->save()) {
            return $this->responseDatabaseSaveError();
        }

        EUpdate::dispatchAfterResponse($equipmentType->id, $equipmentType);

        return response()->json([
            'message' => __('app.equipment_type.update'),
            'equipment_type' => $equipmentType,
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
        $equipmentType = EquipmentType::findOrFail($id);

        // Delete only own equipments config
        if (! $this->user->perm(Perm::EQUIPMENTS_CONFIG_DELETE_ALL) &&
            Gate::denies('owner', $equipmentType)
        ) {
            return $this->responseNoPermission();
        }

        if (! $equipmentType->delete()) {
            return $this->responseDatabaseDestroyError();
        }

        return response()->json([
            'message' => __('app.equipment_type.destroy'),
        ]);
    }
}
