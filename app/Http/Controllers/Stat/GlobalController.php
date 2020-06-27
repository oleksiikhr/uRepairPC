<?php declare(strict_types=1);

namespace App\Http\Controllers\Stat;

use App\Enums\Perm;
use App\Http\Json\GlobalFile;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\GlobalRequest;
use App\Realtime\Settings\EGlobalUpdate;
use App\Http\Resources\GlobalJsonResource;

class GlobalController extends Controller
{
    /**
     * @inheritDoc
     */
    public function permissions(): array
    {
        return [
            'store' => Perm::GLOBAL_SETTINGS_EDIT,
        ];
    }

    /**
     * Display a listing of the resource
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $json = (new GlobalFile)->getData();

        return response()->json(new GlobalJsonResource($json));
    }

    /**
     * Update all resources in storage
     *
     * @param  GlobalRequest  $request
     * @return JsonResponse
     */
    public function store(GlobalRequest $request): JsonResponse
    {
        $globalFile = new GlobalFile;
        $data = $request->validated();

        $globalFile->transformDataAndRequestFiles($data);
        $globalFile->mergeAndSaveToFile($data);

        $jsonResource = new GlobalJsonResource($data);
        EGlobalUpdate::dispatchAfterResponse($jsonResource->jsonSerialize());

        return response()->json([
            'message' => __('app.settings.global'),
            'data' => $jsonResource,
        ]);
    }
}
