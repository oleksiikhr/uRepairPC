<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\Perm;
use App\Models\RequestStatus;
use Illuminate\Http\JsonResponse;
use App\Realtime\RequestStatuses\EJoin;
use App\Realtime\RequestStatuses\ECreate;
use App\Realtime\RequestStatuses\EUpdate;
use App\Http\Requests\RequestStatusRequest;
use Illuminate\Auth\Access\AuthorizationException;

class RequestStatusController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function permissions(): array
    {
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
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $list = RequestStatus::all();

        EJoin::dispatchAfterResponse(...$list);

        return response()->json($list);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  RequestStatusRequest  $request
     * @return JsonResponse
     */
    public function store(RequestStatusRequest $request): JsonResponse
    {
        if ($request->default) {
            RequestStatus::clearDefaultValues();
        }

        $requestStatus = new RequestStatus($request->validated());
        $requestStatus->user_id = auth()->id();
        $requestStatus->save();

        ECreate::dispatchAfterResponse($requestStatus);

        return response()->json([
            'message' => __('app.request_status.store'),
            'request_status' => $requestStatus,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  RequestStatus  $requestStatus
     * @return JsonResponse
     */
    public function show(RequestStatus $requestStatus): JsonResponse
    {
        EJoin::dispatchAfterResponse($requestStatus);

        return response()->json([
            'message' => __('app.request_status.show'),
            'request_status' => $requestStatus,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  RequestStatusRequest  $request
     * @param  RequestStatus  $requestStatus
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function update(RequestStatusRequest $request, RequestStatus $requestStatus): JsonResponse
    {
        $this->authorize('update', $requestStatus);

        // TODO Refactoring
        if ($request->has('default') && $request->default !== $requestStatus->default) {
            if (! $request->default) {
                return response()->json(['message' => __('app.request_status.update_default')], 422);
            }

            RequestStatus::clearDefaultValues();
        }

        $requestStatus->fill($request->validated());
        $requestStatus->save();

        EUpdate::dispatchAfterResponse($requestStatus->id, $requestStatus);

        return response()->json([
            'message' => __('app.request_status.update'),
            'request_status' => $requestStatus,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  RequestStatus  $requestStatus
     * @return JsonResponse
     * @throws AuthorizationException
     * @throws \Exception
     */
    public function destroy(RequestStatus $requestStatus): JsonResponse
    {
        $this->authorize('delete', $requestStatus);

        $requestStatus->delete();

        return response()->json([
            'message' => __('app.request_status.destroy'),
        ]);
    }
}
