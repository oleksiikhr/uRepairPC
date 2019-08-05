<?php

namespace App\Http\Controllers;

use App\User;
use App\Enums\Perm;
use App\EquipmentType;
use Illuminate\Http\Request;
use App\Events\EquipmentTypes\EJoin;
use Illuminate\Support\Facades\Gate;
use App\Events\EquipmentTypes\ECreate;
use App\Events\EquipmentTypes\EUpdate;
use App\Http\Requests\EquipmentTypeRequest;

class EquipmentTypeController extends Controller
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
        $list = EquipmentType::all();

        event(new EJoin(...$list));

        return response()->json($list);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  EquipmentTypeRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(EquipmentTypeRequest $request)
    {
        $equipmentType = new EquipmentType;
        $equipmentType->fill($request->all());
        $equipmentType->user_id = $this->_user->id;

        if (! $equipmentType->save()) {
            return $this->responseDatabaseSaveError();
        }

        event(new ECreate($equipmentType));

        return response()->json([
            'message' => __('app.equipment_type.store'),
            'equipment_type' => $equipmentType,
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
        $equipmentType = EquipmentType::findOrFail($id);

        event(new EJoin($equipmentType));

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
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(EquipmentTypeRequest $request, int $id)
    {
        $equipmentType = EquipmentType::findOrFail($id);

        // Edit only own equipments config
        if (! $this->_user->perm(Perm::EQUIPMENTS_CONFIG_EDIT_ALL) &&
            Gate::denies('owner', $equipmentType)
        ) {
            return $this->responseNoPermission();
        }

        $equipmentType->fill($request->all());

        if (! $equipmentType->save()) {
            return $this->responseDatabaseSaveError();
        }

        event(new EUpdate($equipmentType->id, $equipmentType));

        return response()->json([
            'message' => __('app.equipment_type.update'),
            'equipment_type' => $equipmentType,
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
        $equipmentType = EquipmentType::findOrFail($id);

        // Delete only own equipments config
        if (! $this->_user->perm(Perm::EQUIPMENTS_CONFIG_DELETE_ALL) &&
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
