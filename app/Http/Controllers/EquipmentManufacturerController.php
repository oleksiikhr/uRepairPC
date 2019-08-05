<?php

namespace App\Http\Controllers;

use App\User;
use App\Enums\Perm;
use Illuminate\Http\Request;
use App\EquipmentManufacturer;
use Illuminate\Support\Facades\Gate;
use App\Events\EquipmentManufacturers\EJoin;
use App\Events\EquipmentManufacturers\ECreate;
use App\Events\EquipmentManufacturers\EUpdate;
use App\Http\Requests\EquipmentManufacturerRequest;

class EquipmentManufacturerController extends Controller
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
        $list = EquipmentManufacturer::all();

        event(new EJoin(...$list));

        return response()->json($list);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  EquipmentManufacturerRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(EquipmentManufacturerRequest $request)
    {
        $equipmentManufacturer = new EquipmentManufacturer;
        $equipmentManufacturer->fill($request->all());
        $equipmentManufacturer->user_id = $this->_user->id;

        if (! $equipmentManufacturer->save()) {
            return $this->responseDatabaseSaveError();
        }

        event(new ECreate($equipmentManufacturer));

        return response()->json([
            'message' => __('app.equipment_manufacturers.store'),
            'equipment_manufacturer' => $equipmentManufacturer,
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
        $equipmentManufacturer = EquipmentManufacturer::findOrFail($id);

        event(new EJoin($equipmentManufacturer));

        return response()->json([
            'message' => __('app.equipment_manufacturers.show'),
            'equipment_manufacturer' => $equipmentManufacturer,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  EquipmentManufacturerRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(EquipmentManufacturerRequest $request, int $id)
    {
        $equipmentManufacturer = EquipmentManufacturer::findOrFail($id);

        // Edit only own equipments config
        if (! $this->_user->perm(Perm::EQUIPMENTS_CONFIG_EDIT_ALL) &&
            Gate::denies('owner', $equipmentManufacturer)
        ) {
            return $this->responseNoPermission();
        }

        $equipmentManufacturer->fill($request->all());

        if (! $equipmentManufacturer->save()) {
            return $this->responseDatabaseSaveError();
        }

        event(new EUpdate($equipmentManufacturer->id, $equipmentManufacturer));

        return response()->json([
            'message' => __('app.equipment_manufacturers.update'),
            'equipment_manufacturer' => $equipmentManufacturer,
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
        $equipmentManufacturer = EquipmentManufacturer::findOrFail($id);

        // Delete only own equipments config
        if (! $this->_user->perm(Perm::EQUIPMENTS_CONFIG_DELETE_ALL) &&
            Gate::denies('owner', $equipmentManufacturer)
        ) {
            return $this->responseNoPermission();
        }

        if (! $equipmentManufacturer->delete()) {
            return $this->responseDatabaseDestroyError();
        }

        return response()->json([
            'message' => __('app.equipment_manufacturers.destroy'),
        ]);
    }
}
