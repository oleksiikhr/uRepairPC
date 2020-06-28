<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\Perm;
use App\Models\EquipmentModel;
use Illuminate\Http\JsonResponse;
use App\Realtime\EquipmentModels\EJoin;
use App\Realtime\EquipmentModels\ECreate;
use App\Realtime\EquipmentModels\EUpdate;
use App\Http\Requests\EquipmentModelRequest;
use Illuminate\Auth\Access\AuthorizationException;

class EquipmentModelController extends Controller
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
        $list = EquipmentModel::querySelectJoins()->get();

        EJoin::dispatchAfterResponse(...$list);

        return response()->json($list);
    }

    /**
     * Store a newly created resource in storage
     *
     * @param  EquipmentModelRequest  $request
     * @return JsonResponse
     */
    public function store(EquipmentModelRequest $request): JsonResponse
    {
        $model = new EquipmentModel($request->validated());
        $model->user_id = auth()->id();
        $model->save();

        $model = EquipmentModel::querySelectJoins()->findOrFail($model->id);
        ECreate::dispatchAfterResponse($model);

        return response()->json([
            'message' => __('app.equipment_model.store'),
            'equipment_model' => $model,
        ]);
    }

    /**
     * Display the specified resource
     *
     * @param  EquipmentModel  $equipmentModel
     * @return JsonResponse
     */
    public function show(EquipmentModel $equipmentModel): JsonResponse
    {
        EJoin::dispatchAfterResponse($equipmentModel);

        return response()->json([
            'message' => __('app.equipment_model.show'),
            'equipment_model' => $equipmentModel,
        ]);
    }

    /**
     * Update the specified resource in storage
     *
     * @param  EquipmentModelRequest  $request
     * @param  EquipmentModel  $equipmentModel
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function update(EquipmentModelRequest $request, EquipmentModel $equipmentModel): JsonResponse
    {
        $this->authorize('update', $equipmentModel);

        $equipmentModel->fill($request->validated());
        $equipmentModel->save();

        $equipmentModel = EquipmentModel::querySelectJoins()->findOrFail($equipmentModel->id);
        EUpdate::dispatchAfterResponse($equipmentModel->id, $equipmentModel);

        return response()->json([
            'message' => __('app.equipment_model.update'),
            'equipment_model' => $equipmentModel,
        ]);
    }

    /**
     * Remove the specified resource from storage
     *
     * @param  EquipmentModel  $equipmentModel
     * @return JsonResponse
     * @throws AuthorizationException
     * @throws \Exception
     */
    public function destroy(EquipmentModel $equipmentModel): JsonResponse
    {
        $this->authorize('delete', $equipmentModel);

        $equipmentModel->delete();

        return response()->json([
            'message' => __('app.equipment_model.destroy'),
        ]);
    }
}
