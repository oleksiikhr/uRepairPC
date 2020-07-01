<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\Perm;
use Illuminate\Http\JsonResponse;
use App\Http\Helpers\FilesHelper;
use App\Http\Requests\FileRequest;
use App\Realtime\RequestFiles\EJoin;
use App\Realtime\RequestFiles\ECreate;
use App\Realtime\RequestFiles\EDelete;
use App\Realtime\RequestFiles\EUpdate;
use App\Models\Request as RequestModel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpFoundation\StreamedResponse;

class RequestFileController extends Controller
{
    /**
     * @inheritDoc
     */
    public function permissions(): array
    {
        return [
            'index' => [Perm::REQUESTS_FILES_VIEW_OWN, Perm::REQUESTS_FILES_VIEW_ALL],
            'show' => [Perm::REQUESTS_FILES_DOWNLOAD_OWN, Perm::REQUESTS_FILES_DOWNLOAD_ALL],
            'store' => Perm::REQUESTS_FILES_CREATE,
            'update' => [Perm::REQUESTS_FILES_EDIT_OWN, Perm::REQUESTS_FILES_EDIT_ALL],
            'destroy' => [Perm::REQUESTS_FILES_DELETE_OWN, Perm::REQUESTS_FILES_DELETE_ALL],
        ];
    }

    /**
     * Display a listing of the resource
     *
     * @param  RequestModel  $requestModel
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function index(RequestModel $requestModel): JsonResponse
    {
        $this->authorize('show', $requestModel);

        $query = $requestModel->files();

        if (! perm(Perm::REQUESTS_FILES_VIEW_ALL)) {
            $query->where('user_id', auth()->id());
        }

        $requestFiles = $query->get();
        EJoin::dispatchAfterResponse($requestModel->id, ...$requestFiles);

        return response()->json([
            'message' => __('app.files.files_get'),
            'request_files' => $requestFiles,
        ]);
    }

    /**
     * Display the specified resource
     *
     * @param  RequestModel  $requestModel
     * @param  int  $id
     * @return StreamedResponse|JsonResponse
     * @throws AuthorizationException
     */
    public function show(RequestModel $requestModel, int $id)
    {
        $this->authorize('show', $requestModel);

        $file = $requestModel->files()->findOrFail($id);

        $this->authorize('downloadRequest', $file);

        if (! Storage::exists($file->path)) {
            return response()->json(['message' => __('app.files.file_not_found')], 422);
        }

        return Storage::download($file->path, $file->name.'.'.$file->ext);
    }

    /**
     * Store a newly created resource in storage
     *
     * @param  FileRequest  $request
     * @param  RequestModel  $requestModel
     * @return JsonResponse
     * @throws AuthorizationException
     * @todo refactoring
     */
    public function store(FileRequest $request, RequestModel $requestModel): JsonResponse
    {
        $this->authorize('show', $requestModel);

        $requestFiles = $request->file('files');

        $filesHelper = new FilesHelper($requestFiles);
        $filesHelper->upload('requests/'.$requestModel->id);

        $uploadedIds = $filesHelper->getUploadedIds();
        $requestModel->files()->attach($uploadedIds);
        $uploadedFiles = $requestModel->files()->whereIn('files.id', $uploadedIds)->get();

        if (count($uploadedFiles)) {
            ECreate::dispatchAfterResponse($requestModel->id, $uploadedFiles, auth()->id());
        }

        if ($filesHelper->hasErrors()) {
            return response()->json([
                'message' => __('app.files.upload_error'),
                'errors' => $filesHelper->getErrors(),
                'request_files' => $uploadedFiles,
            ], 422);
        }

        return response()->json([
            'message' => __('app.files.upload_success'),
            'request_files' => $uploadedFiles,
        ]);
    }

    /**
     * Update the specified resource in storage
     *
     * @param  FileRequest  $request
     * @param  RequestModel  $requestModel
     * @param  int  $id
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function update(FileRequest $request, RequestModel $requestModel, int $id): JsonResponse
    {
        $this->authorize('show', $requestModel);

        $file = $requestModel->files()->findOrFail($id);

        $this->authorize('updateRequest', $file);

        $file->name = $request->name;
        $file->save();

        EUpdate::dispatchAfterResponse($requestModel->id, $id, $file);

        return response()->json([
            'message' => __('app.files.file_updated'),
            'request_file' => $file,
        ]);
    }

    /**
     * Remove the specified resource from storage
     *
     * @param  RequestModel  $requestModel
     * @param  int  $id
     * @return JsonResponse
     * @throws AuthorizationException
     * @throws \Exception
     */
    public function destroy(RequestModel $requestModel, int $id): JsonResponse
    {
        $this->authorize('show', $requestModel);

        $file = $requestModel->files()->findOrFail($id);

        $this->authorize('deleteRequest', $file);

        $file->delete();

        EDelete::dispatchAfterResponse($requestModel->id, $file);

        return response()->json([
            'message' => __('app.files.file_destroyed'),
        ]);
    }
}
