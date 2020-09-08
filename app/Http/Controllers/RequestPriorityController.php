<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\Perm;
use App\Models\RequestPriority;
use Illuminate\Http\JsonResponse;
use App\Realtime\RequestPriorities\EJoin;
use App\Realtime\RequestPriorities\ECreate;
use App\Realtime\RequestPriorities\EUpdate;
use App\Http\Requests\RequestPriorityRequest;
use Illuminate\Auth\Access\AuthorizationException;

class RequestPriorityController extends Controller
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
        $list = RequestPriority::all();

        EJoin::dispatchAfterResponse(...$list);

        return response()->json($list);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  RequestPriorityRequest  $request
     * @return JsonResponse
     */
    public function store(RequestPriorityRequest $request): JsonResponse
    {
        if ($request->default) {
            RequestPriority::clearDefaultValues();
        }

        $requestPriority = new RequestPriority($request->validated());
        $requestPriority->user_id = auth()->id();
        $requestPriority->save();

        ECreate::dispatchAfterResponse($requestPriority);

        return response()->json([
            'message' => __('app.request_priority.store'),
            'request_priority' => $requestPriority,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  RequestPriority  $requestPriority
     * @return JsonResponse
     */
    public function show(RequestPriority $requestPriority): JsonResponse
    {
        EJoin::dispatchAfterResponse($requestPriority);

        return response()->json([
            'message' => __('app.request_priority.show'),
            'request_priority' => $requestPriority,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  RequestPriorityRequest  $request
     * @param  RequestPriority  $requestPriority
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function update(RequestPriorityRequest $request, RequestPriority $requestPriority): JsonResponse
    {
        $this->authorize('update', $requestPriority);

        // TODO Refactoring
        if ($request->has('default') && $request->default !== $requestPriority->default) {
            if (! $request->default) {
                return response()->json(['message' => __('app.request_priority.update_default')], 422);
            }

            RequestPriority::clearDefaultValues();
        }

        $requestPriority->fill($request->validated());
        $requestPriority->save();

        EUpdate::dispatchAfterResponse($requestPriority->id, $requestPriority);

        return response()->json([
            'message' => __('app.request_priority.update'),
            'request_priority' => $requestPriority,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  RequestPriority  $requestPriority
     * @return JsonResponse
     * @throws AuthorizationException
     * @throws \Exception
     */
    public function destroy(RequestPriority $requestPriority): JsonResponse
    {
        $this->authorize('delete', $requestPriority);

        $requestPriority->delete();

        return response()->json([
            'message' => __('app.request_priority.destroy'),
        ]);
    }
}
