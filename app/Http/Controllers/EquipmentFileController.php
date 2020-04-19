<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\User;
use App\Equipment;
use App\Enums\Perm;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Helpers\FilesHelper;
use App\Http\Requests\FileRequest;
use App\Events\EquipmentFiles\EJoin;
use Illuminate\Support\Facades\Gate;
use App\Events\EquipmentFiles\ECreate;
use App\Events\EquipmentFiles\EDelete;
use App\Events\EquipmentFiles\EUpdate;
use Illuminate\Support\Facades\Storage;

class EquipmentFileController extends Controller
{
    /**
     * @var Equipment
     */
    private $equipment;

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

        if (! $this->user) {
            $this->middleware('jwt.auth');

            return [];
        }

        $equipmentId = (int) $request->route('equipment');
        $this->equipment = Equipment::findOrFail($equipmentId);

        // Permissions on equipment before get a files
        if (! $this->user->perm(Perm::EQUIPMENTS_VIEW_ALL) &&
            Gate::denies('owner', $this->equipment)
        ) {
            $this->middleware('permission:disable');

            return [];
        }

        return [
            'index' => [Perm::EQUIPMENTS_FILES_VIEW_OWN, Perm::EQUIPMENTS_FILES_VIEW_ALL],
            'show' => [Perm::EQUIPMENTS_FILES_DOWNLOAD_OWN, Perm::EQUIPMENTS_FILES_DOWNLOAD_ALL],
            'store' => Perm::EQUIPMENTS_FILES_CREATE,
            'update' => [Perm::EQUIPMENTS_FILES_EDIT_OWN, Perm::EQUIPMENTS_FILES_EDIT_ALL],
            'destroy' => [Perm::EQUIPMENTS_FILES_DELETE_OWN, Perm::EQUIPMENTS_FILES_DELETE_ALL],
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @param  int  $equipmentId
     * @return JsonResponse
     */
    public function index(int $equipmentId): JsonResponse
    {
        $query = $this->equipment->files();

        if (! $this->user->perm(Perm::EQUIPMENTS_FILES_VIEW_ALL)) {
            $query->where('user_id', $this->user->id);
        }

        $equipmentFiles = $query->get();
        event(new EJoin($equipmentId, ...$equipmentFiles));

        return response()->json([
            'message' => __('app.files.files_get'),
            'equipment_files' => $equipmentFiles,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  FileRequest  $request
     * @param  int  $equipmentId
     * @return JsonResponse
     */
    public function store(FileRequest $request, int $equipmentId): JsonResponse
    {
        $requestFiles = $request->file('files');

        $filesHelper = new FilesHelper($requestFiles);
        $filesHelper->upload('equipments/'.$equipmentId);

        $uploadedIds = $filesHelper->getUploadedIds();
        $this->equipment->files()->attach($uploadedIds);
        $uploadedFiles = $this->equipment->files()->whereIn('files.id', $uploadedIds)->get();

        if (count($uploadedFiles)) {
            event(new ECreate($equipmentId, $uploadedFiles, $this->user->id));
        }

        if ($filesHelper->hasErrors()) {
            return response()->json([
                'message' => __('app.files.upload_error'),
                'errors' => $filesHelper->getErrors(),
                'equipment_files' => $uploadedFiles,
            ], 422);
        }

        return response()->json([
            'message' => __('app.files.upload_success'),
            'equipment_files' => $uploadedFiles,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $equipmentId
     * @param  int  $fileId
     * @return JsonResponse
     */
    public function show(int $equipmentId, int $fileId): JsonResponse
    {
        $equipmentFile = $this->equipment->files()->findOrFail($fileId);

        // Download only own file
        if (! $this->user->perm(Perm::EQUIPMENTS_FILES_DOWNLOAD_ALL) &&
            Gate::denies('owner', $equipmentFile)
        ) {
            return $this->responseNoPermission();
        }

        if (! Storage::exists($equipmentFile->path)) {
            return response()->json(['message' => __('app.files.file_not_found')], 422);
        }

        return Storage::download($equipmentFile->path, $equipmentFile->name.'.'.$equipmentFile->ext);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  FileRequest  $request
     * @param  int  $equipmentId
     * @param  int  $fileId
     * @return JsonResponse
     */
    public function update(FileRequest $request, int $equipmentId, int $fileId): JsonResponse
    {
        $equipmentFile = $this->equipment->files()->findOrFail($fileId);

        // Edit only own file
        if (! $this->user->perm(Perm::EQUIPMENTS_FILES_EDIT_ALL) &&
            Gate::denies('owner', $equipmentFile)
        ) {
            return $this->responseNoPermission();
        }

        $equipmentFile->name = $request->name;

        if (! $equipmentFile->save()) {
            return $this->responseDatabaseSaveError();
        }

        event(new EUpdate($equipmentId, $fileId, $equipmentFile));

        return response()->json([
            'message' => __('app.files.file_updated'),
            'equipment_file' => $equipmentFile,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $equipmentId
     * @param  int  $fileId
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy(int $equipmentId, int $fileId): JsonResponse
    {
        $equipmentFile = $this->equipment->files()->findOrFail($fileId);

        // Delete only own file
        if (! $this->user->perm(Perm::EQUIPMENTS_FILES_DELETE_ALL) &&
            Gate::denies('owner', $equipmentFile)
        ) {
            return $this->responseNoPermission();
        }

        if (! $equipmentFile->delete()) {
            return $this->responseDatabaseDestroyError();
        }

        event(new EDelete($equipmentId, $equipmentFile));

        return response()->json([
            'message' => __('app.files.file_destroyed'),
        ]);
    }
}
