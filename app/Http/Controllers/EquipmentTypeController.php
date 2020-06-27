<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\Perm;
use App\Models\EquipmentType;
use Illuminate\Http\JsonResponse;
use App\Realtime\EquipmentTypes\EJoin;
use App\Realtime\EquipmentTypes\ECreate;
use App\Realtime\EquipmentTypes\EUpdate;
use App\Http\Requests\EquipmentTypeRequest;
use Illuminate\Auth\Access\AuthorizationException;

class EquipmentTypeController extends Controller
{
    /**
     * @inheritDoc
     */
    public function permissions(): array
    {
        return [
            'index' => Perm::EQUIPMENTS_CONFIG_VIEW_ALL,
            'show' => Perm::EQUIPMENTS_CONFIG_VIEW_ALL,
            'store' => Perm::EQUIPMENTS_CONFIG_CREATE,
            'update' => [Perm::EQUIPMENTS_CONFIG_EDIT_OWN, Perm::EQUIPMENTS_CONFIG_EDIT_ALL],
            'destroy' => [Perm::EQUIPMENTS_CONFIG_DELETE_OWN, Perm::EQUIPMENTS_CONFIG_DELETE_ALL],
        ];
    }

    /**
     * Display a listing of the resource
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
     * Store a newly created resource in storage
     *
     * @param  EquipmentTypeRequest  $request
     * @return JsonResponse
     */
    public function store(EquipmentTypeRequest $request): JsonResponse
    {
        $type = new EquipmentType($request->validated());
        $type->user_id = auth()->id();
        $type->save();

        ECreate::dispatchAfterResponse($type);

        return response()->json([
            'message' => __('app.equipment_type.store'),
            'equipment_type' => $type,
        ]);
    }

    /**
     * Display the specified resource
     *
     * @param  EquipmentType  $type
     * @return JsonResponse
     */
    public function show(EquipmentType $type): JsonResponse
    {
        EJoin::dispatchAfterResponse($type);

        return response()->json([
            'message' => __('app.equipment_type.show'),
            'equipment_type' => $type,
        ]);
    }

    /**
     * Update the specified resource in storage
     *
     * @param  EquipmentTypeRequest  $request
     * @param  EquipmentType  $type
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function update(EquipmentTypeRequest $request, EquipmentType $type): JsonResponse
    {
        $this->authorize('update', $type);

        $type->fill($request->validated());
        $type->save();

        EUpdate::dispatchAfterResponse($type->id, $type);

        return response()->json([
            'message' => __('app.equipment_type.update'),
            'equipment_type' => $type,
        ]);
    }

    /**
     * Remove the specified resource from storage
     *
     * @param  EquipmentType  $type
     * @return JsonResponse
     * @throws AuthorizationException
     * @throws \Exception
     */
    public function destroy(EquipmentType $type): JsonResponse
    {
        $this->authorize('delete', $type);

        $type->delete();

        return response()->json([
            'message' => __('app.equipment_type.destroy'),
        ]);
    }
}
