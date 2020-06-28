<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\Perm;
use App\Models\Equipment;
use App\Models\RequestType;
use App\Models\RequestStatus;
use App\Models\RequestPriority;
use App\Realtime\Requests\EJoin;
use App\Http\Helpers\FilesHelper;
use Illuminate\Http\JsonResponse;
use App\Realtime\Requests\ECreate;
use App\Realtime\Requests\EUpdate;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\RequestRequest;
use App\Models\Request as RequestModel;
use Illuminate\Auth\Access\AuthorizationException;

class RequestController extends Controller
{
    /**
     * @inheritDoc
     */
    public function permissions(): array
    {
        return [
            'index' => [Perm::REQUESTS_VIEW_OWN, Perm::REQUESTS_VIEW_ALL, Perm::REQUESTS_VIEW_ASSIGN],
            'show' => [Perm::REQUESTS_VIEW_OWN, Perm::REQUESTS_VIEW_ALL, Perm::REQUESTS_VIEW_ASSIGN],
            'store' => Perm::REQUESTS_CREATE,
            'update' => [Perm::REQUESTS_EDIT_OWN, Perm::REQUESTS_EDIT_ALL, Perm::REQUESTS_EDIT_ASSIGN],
            'destroy' => [Perm::REQUESTS_DELETE_OWN, Perm::REQUESTS_DELETE_ALL, Perm::REQUESTS_DELETE_ASSIGN],
        ];
    }

    /**
     * Display a listing of the resource
     *
     * @param  RequestRequest  $request
     * @return JsonResponse
     */
    public function index(RequestRequest $request): JsonResponse
    {
        $query = RequestModel::querySelectJoins();

        // Search
        if ($request->has('search') && $request->exists('columns')) {
            foreach ($request->columns as $column) {
                $query->orWhere(RequestModel::SEARCH_RELATIONSHIP[$column] ?? $column, 'LIKE', $request->search.'%');
            }
        }

        // Order
        if ($request->has('sortColumn')) {
            $query->orderBy(
                RequestModel::SORT_RELATIONSHIP[$request->sortColumn] ?? $request->sortColumn,
                $request->sortOrder === 'descending' ? 'desc' : 'asc'
            );
        }

        // Filters
        if ($request->priority_id) {
            $query->where('requests.priority_id', $request->priority_id);
        }
        if ($request->status_id) {
            $query->where('requests.status_id', $request->status_id);
        }
        if ($request->type_id) {
            $query->where('requests.type_id', $request->type_id);
        }

        $list = $query->filterByUser(auth()->user())->paginate();
        EJoin::dispatchAfterResponse(...$list->items());

        return response()->json($list);
    }

    /**
     * Store a newly created resource in storage
     *
     * @param  RequestRequest  $request
     * @return JsonResponse
     * @todo refactoring
     */
    public function store(RequestRequest $request): JsonResponse
    {
        $model = new RequestModel($request->validated());
        $model->user_id = auth()->id();
        $model->type_id = RequestType::getDefaultValue()->id;
        $model->priority_id = RequestPriority::getDefaultValue()->id;
        $model->status_id = RequestStatus::getDefaultValue()->id;

        // FIXME Move to another method + in update method + EquipmentFileController (permissions method)
        // + EquipmentController (show method)
        // Add Equipment if has access
        if ($request->has('equipment_id')) {
            if (auth()->user()->perm(Perm::EQUIPMENTS_VIEW_ALL)) {
                $model->equipment_id = $request->equipment_id;
            } else {
                $equipment = Equipment::findOrFail($request->equipment_id);
                if (Gate::allows('owner', $equipment)) {
                    $model->equipment_id = $request->equipment_id;
                }
            }
        }

        $model->save();

        $model = RequestModel::querySelectJoins()->findOrFail($model->id);
        ECreate::dispatchAfterResponse($model);

        return response()->json([
            'message' => __('app.requests.store'),
            'request' => $model,
        ]);
    }

    /**
     * Display the specified resource
     *
     * @param  RequestModel  $requestModel
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function show(RequestModel $requestModel): JsonResponse
    {
        $this->authorize('show', $requestModel);

        EJoin::dispatchAfterResponse($requestModel);

        return response()->json([
            'message' => __('app.requests.show'),
            'request' => $requestModel,
        ]);
    }

    /**
     * Update the specified resource in storage
     *
     * @param  RequestRequest  $request
     * @param  RequestModel  $model
     * @return JsonResponse
     * @throws AuthorizationException
     * @todo refactoring
     */
    public function update(RequestRequest $request, RequestModel $model): JsonResponse
    {
        $this->authorize('update', $model);

        $model->fill($request->validated());

        // Change Equipment if has access
        if ($request->has('equipment_id')) {
            if (auth()->user()->perm(Perm::EQUIPMENTS_VIEW_ALL)) {
                $model->equipment_id = $request->equipment_id;
            } else {
                $equipment = Equipment::findOrFail($request->equipment_id);
                if (Gate::allows('owner', $equipment)) {
                    $model->equipment_id = $request->equipment_id;
                }
            }
        }

        // Only user, who can edit every request - can assign user to request
        // TODO Move to another method (after web system*)
        if ($request->has('assign_id') && auth()->user()->perm(Perm::REQUESTS_EDIT_ALL)) {
            $model->assign_id = $request->assign_id;
        }

        // Config attributes
        if (auth()->user()->perm(Perm::REQUESTS_CONFIG_VIEW_ALL)) {
            if ($request->has('type_id')) {
                $model->type_id = $request->type_id;
            }
            if ($request->has('priority_id')) {
                $model->priority_id = $request->priority_id;
            }
            if ($request->has('status_id')) {
                $model->status_id = $request->status_id;
            }
        }

        $model->save();

        $model = RequestModel::querySelectJoins()->findOrFail($model->id);
        EUpdate::dispatchAfterResponse($model->id, $model);

        return response()->json([
            'message' => __('app.requests.update'),
            'request' => $model,
        ]);
    }

    /**
     * Remove the specified resource from storage
     *
     * @param  RequestRequest  $request
     * @param  RequestModel  $requestModel
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy(RequestRequest $request, RequestModel $requestModel): JsonResponse
    {
        $this->authorize('delete', $requestModel);

        // Destroy files
        if ($request->files_delete && ! FilesHelper::delete($requestModel->files)) {
            return response()->json(['message' => __('app.files.files_not_deleted')], 422);
        }

        $requestModel->delete();

        return response()->json([
            'message' => __('app.requests.destroy'),
        ]);
    }
}
