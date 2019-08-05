<?php

namespace App\Http\Controllers;

use App\User;
use App\Enums\Perm;
use App\EquipmentModel;
use Illuminate\Http\Request;
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
    private $_user;

    /**
     * Add middleware depends on user permissions.
     *
     * @param  Request  $request
     * @return array
     */
    public function permissions(Request $request): array
    {
        $this->_user = auth()->user();

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
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $list = EquipmentModel::querySelectJoins()->get();

        event(new EJoin(...$list));

        return response()->json($list);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  EquipmentModelRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(EquipmentModelRequest $request)
    {
        $equipmentModel = new EquipmentModel;
        $equipmentModel->fill($request->all());
        $equipmentModel->user_id = $this->_user->id;

        if (! $equipmentModel->save()) {
            return $this->responseDatabaseSaveError();
        }

        $equipmentModel = EquipmentModel::querySelectJoins()->findOrFail($equipmentModel->id);
        event(new ECreate($equipmentModel));

        return response()->json([
            'message' => __('app.equipment_model.store.store'),
            'equipment_model' => $equipmentModel,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id)
    {
        $equipmentModel = EquipmentModel::querySelectJoins()->findOrFail($id);

        event(new EJoin($equipmentModel));

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
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(EquipmentModelRequest $request, int $id)
    {
        $equipmentModel = EquipmentModel::findOrFail($id);

        // Edit only own equipments config
        if (! $this->_user->perm(Perm::EQUIPMENTS_CONFIG_EDIT_ALL) &&
            Gate::denies('owner', $equipmentModel)
        ) {
            return $this->responseNoPermission();
        }

        $equipmentModel->fill($request->all());

        if (! $equipmentModel->save()) {
            return $this->responseDatabaseSaveError();
        }

        $equipmentModel = EquipmentModel::querySelectJoins()->findOrFail($equipmentModel->id);
        event(new EUpdate($equipmentModel->id, $equipmentModel));

        return response()->json([
            'message' => __('app.equipment_model.update'),
            'equipment_model' => $equipmentModel,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $id)
    {
        $equipmentModel = EquipmentModel::findOrFail($id);

        // Delete only own equipments config
        if (! $this->_user->perm(Perm::EQUIPMENTS_CONFIG_DELETE_ALL) &&
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
