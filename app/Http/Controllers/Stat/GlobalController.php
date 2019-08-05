<?php

namespace App\Http\Controllers\Stat;

use App\Enums\Perm;
use Illuminate\Http\Request;
use App\Http\Json\GlobalFile;
use App\Http\Controllers\Controller;
use App\Http\Requests\GlobalRequest;
use App\Events\Settings\EGlobalUpdate;
use App\Http\Resources\GlobalJsonResource;

class GlobalController extends Controller
{
    /**
     * Add middleware depends on user permissions.
     *
     * @param  Request  $request
     * @return array
     */
    public function permissions(Request $request): array
    {
        return [
            'store' => Perm::GLOBAL_SETTINGS_EDIT,
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $json = (new GlobalFile)->getData();

        return response()->json(new GlobalJsonResource($json));
    }

    /**
     * Update all resources in storage.
     *
     * @param  GlobalRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(GlobalRequest $request)
    {
        $globalFile = new GlobalFile;
        $data = $request->validated();

        $globalFile->transformDataAndRequestFiles($data);
        $globalFile->mergeAndSaveToFile($data);

        $jsonResource = new GlobalJsonResource($data);
        event(new EGlobalUpdate($jsonResource->jsonSerialize()));

        return response()->json([
            'message' => __('app.settings.global'),
            'data' => $jsonResource,
        ]);
    }
}
