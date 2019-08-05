<?php

namespace App\Http\Controllers\Stat;

use App\Enums\Perm;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Json\ManifestFile;
use App\Http\Helpers\FileHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\ManifestRequest;
use App\Events\Settings\EManifestUpdate;
use App\Http\Resources\ManifestJsonResource;

class ManifestController extends Controller
{
    /**
     * Keeps default images.
     */
    const PROTECT_FOLDER = 'images';

    /**
     * Add middleware depends on user permissions.
     *
     * @param Request $request
     * @return array
     */
    public function permissions(Request $request): array
    {
        return [
            'store' => Perm::GLOBAL_MANIFEST_EDIT,
        ];
    }

    /**
     * Display a manifest.json.
     *
     * @return void
     */
    public function index()
    {
        $json = (new ManifestFile)->getData();

        return response()->json(new ManifestJsonResource($json));
    }

    /**
     * Update all resources in storage.
     *
     * @param ManifestRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ManifestRequest $request)
    {
        $manifestFile = new ManifestFile;
        $data = $request->validated();

        $manifestFile->transformDataAndRequestFiles($data);

        $existsIcons = array_key_exists('icons', $data);
        $existsRemoveIcons = array_key_exists('remove_icons', $data);

        // Save files
        $icons = $request->file('upload_icons');
        if ($icons) {
            foreach ($icons as $key => $value) {
                $file = new FileHelper($value);
                if ($url = $file->store('global', 'public')) {
                    $size = getimagesize(storage_path('app/public/'.$url));
                    array_push($data['icons'], (object) [
                        'src' => $url,
                        'sizes' => $size[0].'x'.$size[1],
                        'type' => $value->getMimeType(),
                    ]);
                }
            }
        }
        unset($data['upload_icons']);

        // Remove files
        if ($existsIcons && $existsRemoveIcons) {
            foreach ($data['remove_icons'] as $key => $value) {
                $srcCut = Str::after($value, 'storage/');
                $findIndexBySrc = collect($data['icons'])
                    ->search(function ($item, $key) use ($srcCut) {
                        return $item->src === $srcCut;
                    }, true);

                if ($findIndexBySrc !== false) {
                    if (strpos($value, self::PROTECT_FOLDER) === false) {
                        FileHelper::delete($srcCut, 'public');
                    }
                    array_splice($data['icons'], $findIndexBySrc, 1);
                }
            }
        }
        unset($data['remove_icons']);

        $manifestFile->mergeAndSaveToFile($data);

        $response = (new ManifestJsonResource($data))->jsonSerialize();
        event(new EManifestUpdate($response));

        return response()->json([
            'message' => __('app.settings.manifest'),
            'data' => $response,
        ]);
    }
}
