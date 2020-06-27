<?php declare(strict_types=1);

namespace App\Http\Controllers\Stat;

use App\Enums\Perm;
use Illuminate\Support\Str;
use App\Http\Json\ManifestFile;
use App\Http\Helpers\FileHelper;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\ManifestRequest;
use App\Realtime\Settings\EManifestUpdate;
use App\Http\Resources\ManifestJsonResource;

class ManifestController extends Controller
{
    /**
     * Keeps default images
     */
    public const PROTECT_FOLDER = 'images';

    /**
     * @inheritDoc
     */
    public function permissions(): array
    {
        return [
            'store' => Perm::GLOBAL_MANIFEST_EDIT,
        ];
    }

    /**
     * Display a manifest.json
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $json = (new ManifestFile)->getData();

        return response()->json(new ManifestJsonResource($json));
    }

    /**
     * Update all resources in storage
     *
     * @param  ManifestRequest  $request
     * @return JsonResponse
     */
    public function store(ManifestRequest $request): JsonResponse
    {
        // TODO Refactor
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
        EManifestUpdate::dispatchAfterResponse($response);

        return response()->json([
            'message' => __('app.settings.manifest'),
            'data' => $response,
        ]);
    }
}
