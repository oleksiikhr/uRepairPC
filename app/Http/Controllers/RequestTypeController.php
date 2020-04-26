<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\User;
use App\Enums\Perm;
use App\RequestType;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Realtime\RequestTypes\EJoin;
use App\Realtime\RequestTypes\ECreate;
use App\Realtime\RequestTypes\EUpdate;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\RequestTypeRequest;

class RequestTypeController extends Controller
{
    /**
     * @var User
     */
    private $user;

    /**
     * Add middleware depends on user permissions.
     *
     * @param  Request  $request
     * @return array
     */
    public function permissions(Request $request): array
    {
        $this->user = auth()->user();

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
        $requestType = new RequestType;
        $requestType->fill($request->all());
        $requestType->user_id = $this->user->id;

        if ($request->default) {
            RequestType::clearDefaultValues();
        }

        if (! $requestType->save()) {
            return $this->responseDatabaseSaveError();
        }

        ECreate::dispatchAfterResponse($requestType);

        return response()->json([
            'message' => __('app.request_type.store'),
            'request_type' => $requestType,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $requestType = RequestType::findOrFail($id);

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
     * @param  int  $id
     * @return JsonResponse
     */
    public function update(RequestTypeRequest $request, int $id): JsonResponse
    {
        $requestType = RequestType::findOrFail($id);

        // Edit only own requests config
        if (! $this->user->perm(Perm::REQUESTS_CONFIG_EDIT_ALL) &&
            Gate::denies('owner', $requestType)
        ) {
            return $this->responseNoPermission();
        }

        if ($request->has('default') && $request->default !== $requestType->default) {
            if (! $request->default) {
                return response()->json(['message' => __('app.request_type.update_default')], 422);
            }

            RequestType::clearDefaultValues();
        }

        $requestType->fill($request->all());

        if (! $requestType->save()) {
            return $this->responseDatabaseSaveError();
        }

        EUpdate::dispatchAfterResponse($requestType->id, $requestType);

        return response()->json([
            'message' => __('app.request_type.update'),
            'request_type' => $requestType,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $requestType = RequestType::findOrFail($id);

        // Delete only own requests config
        if (! $this->user->perm(Perm::REQUESTS_CONFIG_DELETE_ALL) &&
            Gate::denies('owner', $requestType)
        ) {
            return $this->responseNoPermission();
        }

        if ($requestType->default) {
            return response()->json(['message' => __('app.request_type.destroy_default')], 422);
        }

        if (! $requestType->delete($id)) {
            return $this->responseDatabaseDestroyError();
        }

        return response()->json([
            'message' => __('app.request_type.destroy'),
        ]);
    }
}
