<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\Perm;
use App\Models\RequestType;
use Illuminate\Http\JsonResponse;
use App\Realtime\RequestTypes\EJoin;
use App\Realtime\RequestTypes\ECreate;
use App\Realtime\RequestTypes\EUpdate;
use App\Http\Requests\RequestTypeRequest;
use Illuminate\Auth\Access\AuthorizationException;

class RequestTypeController extends Controller
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
        $list = RequestType::all();

        EJoin::dispatchAfterResponse(...$list);

        return response()->json($list);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  RequestTypeRequest  $request
     * @return JsonResponse
     */
    public function store(RequestTypeRequest $request): JsonResponse
    {
        if ($request->default) {
            RequestType::clearDefaultValues();
        }

        $requestType = new RequestType($request->validated());
        $requestType->user_id = auth()->id();
        $requestType->save();

        ECreate::dispatchAfterResponse($requestType);

        return response()->json([
            'message' => __('app.request_type.store'),
            'request_type' => $requestType,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  RequestType  $requestType
     * @return JsonResponse
     */
    public function show(RequestType $requestType): JsonResponse
    {
        EJoin::dispatchAfterResponse($requestType);

        return response()->json([
            'message' => __('app.request_type.show'),
            'request_type' => $requestType,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  RequestTypeRequest  $request
     * @param  RequestType  $requestType
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function update(RequestTypeRequest $request, RequestType $requestType): JsonResponse
    {
        $this->authorize('update', $requestType);

        // TODO Refactoring
        if ($request->has('default') && $request->default !== $requestType->default) {
            if (! $request->default) {
                return response()->json(['message' => __('app.request_type.update_default')], 422);
            }

            RequestType::clearDefaultValues();
        }

        $requestType->fill($request->validated());
        $requestType->save();

        EUpdate::dispatchAfterResponse($requestType->id, $requestType);

        return response()->json([
            'message' => __('app.request_type.update'),
            'request_type' => $requestType,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  RequestType  $requestType
     * @return JsonResponse
     * @throws AuthorizationException
     * @throws \Exception
     */
    public function destroy(RequestType $requestType): JsonResponse
    {
        $this->authorize('delete', $requestType);

        $requestType->delete();

        return response()->json([
            'message' => __('app.request_type.destroy'),
        ]);
    }
}
