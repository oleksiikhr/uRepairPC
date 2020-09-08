<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\Perm;
use App\Models\Equipment;
use App\Http\Helpers\FilesHelper;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\FileRequest;
use App\Realtime\EquipmentFiles\EJoin;
use Illuminate\Support\Facades\Storage;
use App\Realtime\EquipmentFiles\ECreate;
use App\Realtime\EquipmentFiles\EDelete;
use App\Realtime\EquipmentFiles\EUpdate;
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpFoundation\StreamedResponse;

class EquipmentFileController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function permissions(): array
    {
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
     * @param  Equipment  $equipment
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function index(Equipment $equipment): JsonResponse
    {
        $this->authorize('show', $equipment);

        $query = $equipment->files();

        if (! perm(Perm::EQUIPMENTS_FILES_VIEW_ALL)) {
            $query->where('user_id', auth()->id());
        }

        $equipmentFiles = $query->get();
        EJoin::dispatchAfterResponse($equipment->id, ...$equipmentFiles);

        return response()->json([
            'message' => __('app.files.files_get'),
            'equipment_files' => $equipmentFiles,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  Equipment  $equipment
     * @param  int  $id
     * @return StreamedResponse|JsonResponse
     * @throws AuthorizationException
     */
    public function show(Equipment $equipment, int $id)
    {
        $this->authorize('show', $equipment);

        $file = $equipment->files()->findOrFail($id);

        $this->authorize('downloadEquipment', $file);

        if (! Storage::exists($file->path)) {
            return response()->json(['message' => __('app.files.file_not_found')], 422);
        }

        return Storage::download($file->path, $file->name.'.'.$file->ext);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  FileRequest  $request
     * @param  Equipment  $equipment
     * @return JsonResponse
     * @throws AuthorizationException
     * @todo refactoring
     */
    public function store(FileRequest $request, Equipment $equipment): JsonResponse
    {
        $this->authorize('show', $equipment);

        $requestFiles = $request->file('files');

        $filesHelper = new FilesHelper($requestFiles);
        $filesHelper->upload('equipments/'.$equipment->id);

        $uploadedIds = $filesHelper->getUploadedIds();
        $equipment->files()->attach($uploadedIds);
        $uploadedFiles = $equipment->files()->whereIn('files.id', $uploadedIds)->get();

        if (count($uploadedFiles)) {
            ECreate::dispatchAfterResponse($equipment->id, $uploadedFiles, auth()->id());
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
     * Update the specified resource in storage.
     *
     * @param  FileRequest  $request
     * @param  Equipment  $equipment
     * @param  int  $id
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function update(FileRequest $request, Equipment $equipment, int $id): JsonResponse
    {
        $this->authorize('show', $equipment);

        $file = $equipment->files()->findOrFail($id);

        $this->authorize('updateEquipment', $file);

        $file->name = $request->name;
        $file->save();

        EUpdate::dispatchAfterResponse($equipment->id, $id, $file);

        return response()->json([
            'message' => __('app.files.file_updated'),
            'equipment_file' => $file,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Equipment  $equipment
     * @param  int  $id
     * @return JsonResponse
     * @throws AuthorizationException
     * @throws \Exception
     */
    public function destroy(Equipment $equipment, int $id): JsonResponse
    {
        $this->authorize('show', $equipment);

        $file = $equipment->files()->findOrFail($id);

        $this->authorize('deleteEquipment', $file);

        $file->delete();

        EDelete::dispatchAfterResponse($equipment->id, $file);

        return response()->json([
            'message' => __('app.files.file_destroyed'),
        ]);
    }
}
