<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\Perm;
use App\Models\Equipment;
use Illuminate\Http\JsonResponse;
use App\Http\Helpers\FilesHelper;
use App\Realtime\Equipments\EJoin;
use App\Realtime\Equipments\ECreate;
use App\Realtime\Equipments\EUpdate;
use App\Http\Requests\EquipmentRequest;
use Illuminate\Auth\Access\AuthorizationException;

class EquipmentController extends Controller
{
    /**
     * @inheritDoc
     */
    public function permissions(): array
    {
        return [
            'index' => [Perm::EQUIPMENTS_VIEW_ALL, Perm::EQUIPMENTS_VIEW_OWN],
            'show' => [Perm::EQUIPMENTS_VIEW_ALL, Perm::EQUIPMENTS_VIEW_OWN],
            'store' => Perm::EQUIPMENTS_CREATE,
            'update' => [Perm::EQUIPMENTS_EDIT_ALL, Perm::EQUIPMENTS_EDIT_OWN],
            'destroy' => [Perm::EQUIPMENTS_DELETE_ALL, Perm::EQUIPMENTS_DELETE_OWN],
        ];
    }

    /**
     * Display a listing of the resource
     *
     * @param  EquipmentRequest  $request
     * @return JsonResponse
     */
    public function index(EquipmentRequest $request): JsonResponse
    {
        $query = Equipment::querySelectJoins();

        // Search
        if ($request->has('search') && $request->exists('columns')) {
            foreach ($request->columns as $column) {
                $query->orWhere(Equipment::SEARCH_RELATIONSHIP[$column] ?? $column, 'LIKE', $request->search.'%');
            }
        }

        // Order
        if ($request->has('sortColumn')) {
            $query->orderBy($request->sortColumn, $request->sortOrder === 'descending' ? 'desc' : 'asc');
        }

        // Show only own equipments
        if (! perm(Perm::EQUIPMENTS_VIEW_ALL)) {
            $query->where('user_id', auth()->id());
        }

        $list = $query->paginate();
        EJoin::dispatchAfterResponse(...$list->items());

        return response()->json($list);
    }

    /**
     * Store a newly created resource in storage
     *
     * @param  EquipmentRequest  $request
     * @return JsonResponse
     */
    public function store(EquipmentRequest $request): JsonResponse
    {
        $equipment = new Equipment($request->validated());
        $equipment->user_id = auth()->id();
        $equipment->save();

        $equipment = Equipment::querySelectJoins()->findOrFail($equipment->id);
        ECreate::dispatchAfterResponse($equipment);

        return response()->json([
            'message' => __('app.equipments.store'),
            'equipment' => $equipment,
        ]);
    }

    /**
     * Display the specified resource
     *
     * @param  Equipment  $equipment
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function show(Equipment $equipment): JsonResponse
    {
        $this->authorize('show', $equipment);

        $equipment = Equipment::querySelectJoins()->findOrFail($equipment->id);

        EJoin::dispatchAfterResponse($equipment);

        return response()->json([
            'message' => __('app.equipments.show'),
            'equipment' => $equipment,
        ]);
    }

    /**
     * Update the specified resource in storage
     *
     * @param  EquipmentRequest  $request
     * @param  Equipment  $equipment
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function update(EquipmentRequest $request, Equipment $equipment): JsonResponse
    {
        $this->authorize('update', $equipment);

        $equipment->fill($request->validated());
        $equipment->save();

        // Update model new data from relationship
        $equipment = Equipment::querySelectJoins()->findOrFail($equipment->id);
        EUpdate::dispatchAfterResponse($equipment->id, $equipment);

        return response()->json([
            'message' => __('app.equipments.update'),
            'equipment' => $equipment,
        ]);
    }

    /**
     * Remove the specified resource from storage
     *
     * @param  EquipmentRequest  $request
     * @param  Equipment  $equipment
     * @return JsonResponse
     * @throws AuthorizationException
     * @throws \Exception
     */
    public function destroy(EquipmentRequest $request, Equipment $equipment): JsonResponse
    {
        $this->authorize('delete', $equipment);

        // TODO
        if ($request->files_delete && ! FilesHelper::delete($equipment->files)) {
            return response()->json(['message' => __('app.files.files_not_deleted')], 422);
        }

        $equipment->delete();

        return response()->json([
            'message' => __('app.equipments.destroy'),
        ]);
    }
}
