<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\User;
use App\Enums\Perm;
use App\RequestPriority;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use App\Events\RequestPriorities\EJoin;
use App\Events\RequestPriorities\ECreate;
use App\Events\RequestPriorities\EUpdate;
use App\Http\Requests\RequestPriorityRequest;

class RequestPriorityController extends Controller
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
        $list = RequestPriority::all();

        event(new EJoin(...$list));

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
        $requestPriority = new RequestPriority;
        $requestPriority->fill($request->all());
        $requestPriority->user_id = $this->user->id;

        if ($request->default) {
            RequestPriority::clearDefaultValues();
        }

        if (! $requestPriority->save()) {
            return $this->responseDatabaseSaveError();
        }

        event(new ECreate($requestPriority));

        return response()->json([
            'message' => __('app.request_priority.store'),
            'request_priority' => $requestPriority,
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
        $requestPriority = RequestPriority::findOrFail($id);

        event(new EJoin($requestPriority));

        return response()->json([
            'message' => __('app.request_priority.show'),
            'request_priority' => $requestPriority,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  RequestPriorityRequest  $request
     * @param  int  $id
     * @return JsonResponse
     */
    public function update(RequestPriorityRequest $request, int $id): JsonResponse
    {
        $requestPriority = RequestPriority::findOrFail($id);

        // Edit only own requests config
        if (! $this->user->perm(Perm::REQUESTS_CONFIG_EDIT_ALL) &&
            Gate::denies('owner', $requestPriority)
        ) {
            return $this->responseNoPermission();
        }

        if ($request->has('default') && $request->default !== $requestPriority->default) {
            if (! $request->default) {
                return response()->json(['message' => __('app.request_priority.update_default')], 422);
            }

            RequestPriority::clearDefaultValues();
        }

        $requestPriority->fill($request->all());

        if (! $requestPriority->save()) {
            return $this->responseDatabaseSaveError();
        }

        event(new EUpdate($requestPriority->id, $requestPriority));

        return response()->json([
            'message' => __('app.request_priority.update'),
            'request_priority' => $requestPriority,
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
        $requestPriority = RequestPriority::findOrFail($id);

        // Delete only own requests config
        if (! $this->user->perm(Perm::REQUESTS_CONFIG_DELETE_ALL) &&
            Gate::denies('owner', $requestPriority)
        ) {
            return $this->responseNoPermission();
        }

        if ($requestPriority->default) {
            return response()->json(['message' => __('app.request_priority.destroy_default')], 422);
        }

        if (! $requestPriority->delete($id)) {
            return $this->responseDatabaseDestroyError();
        }

        return response()->json([
            'message' => __('app.request_priority.destroy'),
        ]);
    }
}
