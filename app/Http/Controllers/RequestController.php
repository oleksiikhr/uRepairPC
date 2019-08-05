<?php

namespace App\Http\Controllers;

use App\User;
use App\Equipment;
use App\Enums\Perm;
use App\RequestType;
use App\RequestStatus;
use App\RequestPriority;
use Illuminate\Http\Request;
use App\Events\Requests\EJoin;
use App\Events\Requests\ECreate;
use App\Events\Requests\EUpdate;
use App\Request as RequestModel;
use App\Http\Helpers\FilesHelper;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\RequestRequest;

class RequestController extends Controller
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
            'index' => [Perm::REQUESTS_VIEW_OWN, Perm::REQUESTS_VIEW_ALL, Perm::REQUESTS_VIEW_ASSIGN],
            'show' => [Perm::REQUESTS_VIEW_OWN, Perm::REQUESTS_VIEW_ALL, Perm::REQUESTS_VIEW_ASSIGN],
            'store' => Perm::REQUESTS_CREATE,
            'update' => [Perm::REQUESTS_EDIT_OWN, Perm::REQUESTS_EDIT_ALL, Perm::REQUESTS_EDIT_ASSIGN],
            'destroy' => [Perm::REQUESTS_DELETE_OWN, Perm::REQUESTS_DELETE_ALL, Perm::REQUESTS_DELETE_ASSIGN],
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @param  RequestRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(RequestRequest $request)
    {
        $query = RequestModel::querySelectJoins();

        // Search
        if ($request->has('search') && $request->has('columns') && ! empty($request->columns)) {
            foreach ($request->columns as $column) {
                $query->orWhere(RequestModel::SEARCH_RELATIONSHIP[$column] ?? $column, 'LIKE', '%'.$request->search.'%');
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

        // Get requests by permissions
        RequestModel::buildQueryByPerm($query, $this->_user);

        $list = $query->paginate(self::PAGINATE_DEFAULT);
        event(new EJoin(...$list->items()));

        return response()->json($list);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  RequestRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(RequestRequest $request)
    {
        $requestModel = new RequestModel;
        $requestModel->fill($request->all());
        $requestModel->user_id = $this->_user->id;
        $requestModel->type_id = RequestType::getDefaultValue()->id;
        $requestModel->priority_id = RequestPriority::getDefaultValue()->id;
        $requestModel->status_id = RequestStatus::getDefaultValue()->id;

        // FIXME Move to another method + in update method + EquipmentFileController (permissions method)
        // + EquipmentController (show method)
        // Add Equipment if has access
        if ($request->has('equipment_id')) {
            if ($this->_user->perm(Perm::EQUIPMENTS_VIEW_ALL)) {
                $requestModel->equipment_id = $request->equipment_id;
            } else {
                $equipment = Equipment::findOrFail($request->equipment_id);
                if (Gate::allows('owner', $equipment)) {
                    $requestModel->equipment_id = $request->equipment_id;
                }
            }
        }

        if (! $requestModel->save()) {
            return $this->responseDatabaseSaveError();
        }

        $requestModel = RequestModel::querySelectJoins()->findOrFail($requestModel->id);
        event(new ECreate($requestModel));

        return response()->json([
            'message' => __('app.requests.store'),
            'request' => RequestModel::querySelectJoins()->findOrFail($requestModel->id),
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
        $requestModel = RequestModel::querySelectJoins()->findOrFail($id);

        if (! RequestModel::hasAccessByPerm($requestModel, $this->_user)) {
            return $this->responseNoPermission();
        }

        event(new EJoin($requestModel));

        return response()->json([
            'message' => __('app.requests.show'),
            'request' => $requestModel,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  RequestRequest  $request
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(RequestRequest $request, int $id)
    {
        $requestModel = RequestModel::findOrFail($id);

        if (! RequestModel::hasAccessByPerm($requestModel, $this->_user)) {
            return $this->responseNoPermission();
        }

        $requestModel->fill($request->all());

        // Change Equipment if has access
        if ($request->has('equipment_id')) {
            if ($this->_user->perm(Perm::EQUIPMENTS_VIEW_ALL)) {
                $requestModel->equipment_id = $request->equipment_id;
            } else {
                $equipment = Equipment::findOrFail($request->equipment_id);
                if (Gate::allows('owner', $equipment)) {
                    $requestModel->equipment_id = $request->equipment_id;
                }
            }
        }

        // Only user, who can edit every request - can assign user to request
        // TODO Move to another method (after web system*)
        if ($request->has('assign_id') && $this->_user->perm(Perm::REQUESTS_EDIT_ALL)) {
            $requestModel->assign_id = $request->assign_id;
        }

        // Config attributes
        if ($this->_user->perm(Perm::REQUESTS_CONFIG_VIEW_ALL)) {
            if ($request->has('type_id')) {
                $requestModel->type_id = $request->type_id;
            }
            if ($request->has('priority_id')) {
                $requestModel->priority_id = $request->priority_id;
            }
            if ($request->has('status_id')) {
                $requestModel->status_id = $request->status_id;
            }
        }

        if (! $requestModel->save()) {
            return $this->responseDatabaseSaveError();
        }

        $requestModel = RequestModel::querySelectJoins()->findOrFail($id);
        event(new EUpdate($requestModel->id, $requestModel));

        return response()->json([
            'message' => __('app.requests.update'),
            'request' => $requestModel,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  RequestRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(RequestRequest $request, int $id)
    {
        $requestModel = RequestModel::findOrFail($id);

        if (! RequestModel::hasAccessByPerm($requestModel, $this->_user)) {
            return $this->responseNoPermission();
        }

        // Destroy files
        if ($request->files_delete) {
            if (! FilesHelper::delete($requestModel->files)) {
                return response()->json(['message' => __('app.files.files_not_deleted')], 422);
            }
        }

        if (! $requestModel->delete()) {
            return $this->responseDatabaseDestroyError();
        }

        return response()->json([
            'message' => __('app.requests.destroy'),
        ]);
    }
}
