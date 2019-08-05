<?php

namespace App\Http\Controllers;

use App\User;
use App\Enums\Perm;
use App\RequestStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Events\RequestStatuses\EJoin;
use App\Events\RequestStatuses\ECreate;
use App\Events\RequestStatuses\EUpdate;
use App\Http\Requests\RequestStatusRequest;

class RequestStatusController extends Controller
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
            'index' => Perm::REQUESTS_CONFIG_VIEW_ALL,
            'show' => Perm::REQUESTS_CONFIG_VIEW_ALL,
            'store' => Perm::REQUESTS_CONFIG_CREATE,
            'update' => [Perm::REQUESTS_CONFIG_EDIT_OWN, Perm::REQUESTS_CONFIG_EDIT_ALL],
            'destroy' => [Perm::REQUESTS_CONFIG_DELETE_OWN, Perm::REQUESTS_CONFIG_DELETE_ALL],
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $list = RequestStatus::all();

        event(new EJoin(...$list));

        return response()->json($list);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  RequestStatusRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(RequestStatusRequest $request)
    {
        $requestStatus = new RequestStatus;
        $requestStatus->fill($request->all());
        $requestStatus->user_id = $this->_user->id;

        if ($request->default) {
            RequestStatus::clearDefaultValues();
        }

        if (! $requestStatus->save()) {
            return $this->responseDatabaseSaveError();
        }

        event(new ECreate($requestStatus));

        return response()->json([
            'message' => __('app.request_status.store'),
            'request_status' => $requestStatus,
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
        $requestStatus = RequestStatus::findOrFail($id);

        event(new EJoin($requestStatus));

        return response()->json([
            'message' => __('app.request_status.show'),
            'request_status' => $requestStatus,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  RequestStatusRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(RequestStatusRequest $request, int $id)
    {
        $requestStatus = RequestStatus::findOrFail($id);

        // Edit only own requests config
        if (! $this->_user->perm(Perm::REQUESTS_CONFIG_EDIT_ALL) &&
            Gate::denies('owner', $requestStatus)
        ) {
            return $this->responseNoPermission();
        }

        if ($request->has('default') && $request->default !== $requestStatus->default) {
            if (! $request->default) {
                return response()->json(['message' => __('app.request_status.update_default')], 422);
            }

            RequestStatus::clearDefaultValues();
        }

        $requestStatus->fill($request->all());

        if (! $requestStatus->save()) {
            return $this->responseDatabaseSaveError();
        }

        event(new EUpdate($requestStatus->id, $requestStatus));

        return response()->json([
            'message' => __('app.request_status.update'),
            'request_status' => $requestStatus,
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
        $requestStatus = RequestStatus::findOrFail($id);

        // Delete only own requests config
        if (! $this->_user->perm(Perm::REQUESTS_CONFIG_DELETE_ALL) &&
            Gate::denies('owner', $requestStatus)
        ) {
            return $this->responseNoPermission();
        }

        if ($requestStatus->default) {
            return response()->json(['message' => __('app.request_status.destroy_default')], 422);
        }

        if (! $requestStatus->delete($id)) {
            return $this->responseDatabaseDestroyError();
        }

        return response()->json([
            'message' => __('app.request_status.destroy'),
        ]);
    }
}
