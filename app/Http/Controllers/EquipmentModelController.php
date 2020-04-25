<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\User;
use App\Enums\Perm;
use App\EquipmentModel;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use App\Events\EquipmentModels\EJoin;
use App\Events\EquipmentModels\ECreate;
use App\Events\EquipmentModels\EUpdate;
use App\Http\Requests\EquipmentModelRequest;

class EquipmentModelController extends Controller
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
        $list = EquipmentModel::querySelectJoins()->get();

        EJoin::dispatchAfterResponse(...$list);

        return response()->json($list);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  EquipmentModelRequest  $request
     * @return JsonResponse
     */
    public function store(EquipmentModelRequest $request): JsonResponse
    {
        $equipmentModel = new EquipmentModel;
        $equipmentModel->fill($request->all());
        $equipmentModel->user_id = $this->user->id;

        if (! $equipmentModel->save()) {
            return $this->responseDatabaseSaveError();
        }

        $equipmentModel = EquipmentModel::querySelectJoins()->findOrFail($equipmentModel->id);
        ECreate::dispatchAfterResponse($equipmentModel);

        return response()->json([
            'message' => __('app.equipment_model.store.store'),
            'equipment_model' => $equipmentModel,
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
        $equipmentModel = EquipmentModel::querySelectJoins()->findOrFail($id);

        EJoin::dispatchAfterResponse($equipmentModel);

        return response()->json([
            'message' => __('app.equipment_model.show'),
            'equipment_model' => $equipmentModel,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  EquipmentModelRequest  $request
     * @param  int  $id
     * @return JsonResponse
     */
    public function update(EquipmentModelRequest $request, int $id): JsonResponse
    {
        $equipmentModel = EquipmentModel::findOrFail($id);

        // Edit only own equipments config
        if (! $this->user->perm(Perm::EQUIPMENTS_CONFIG_EDIT_ALL) &&
            Gate::denies('owner', $equipmentModel)
        ) {
            return $this->responseNoPermission();
        }

        $equipmentModel->fill($request->all());

        if (! $equipmentModel->save()) {
            return $this->responseDatabaseSaveError();
        }

        $equipmentModel = EquipmentModel::querySelectJoins()->findOrFail($equipmentModel->id);
        EUpdate::dispatchAfterResponse($equipmentModel->id, $equipmentModel);

        return response()->json([
            'message' => __('app.equipment_model.update'),
            'equipment_model' => $equipmentModel,
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
        $equipmentModel = EquipmentModel::findOrFail($id);

        // Delete only own equipments config
        if (! $this->user->perm(Perm::EQUIPMENTS_CONFIG_DELETE_ALL) &&
            Gate::denies('owner', $equipmentModel)
        ) {
            return $this->responseNoPermission();
        }

        if (! $equipmentModel->delete()) {
            return $this->responseDatabaseDestroyError();
        }

        return response()->json([
            'message' => __('app.equipment_model.destroy'),
        ]);
    }
}
