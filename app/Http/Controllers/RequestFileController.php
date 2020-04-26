<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\User;
use App\Enums\Perm;
use Illuminate\Http\Request;
use App\Request as RequestModel;
use Illuminate\Http\JsonResponse;
use App\Http\Helpers\FilesHelper;
use App\Realtime\RequestFiles\EJoin;
use App\Http\Requests\FileRequest;
use App\Realtime\RequestFiles\ECreate;
use App\Realtime\RequestFiles\EDelete;
use App\Realtime\RequestFiles\EUpdate;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class RequestFileController extends Controller
{
    /**
     * @var RequestModel
     */
    private $request;

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

        $requestId = (int) $request->route('request');
        $this->request = RequestModel::findOrFail($requestId);

        // Permissions on request before get a files
        if (! RequestModel::hasAccessByPerm($this->request, $this->user)) {
            $this->middleware('permission:disable');

            return [];
        }

        return [
            'index' => [Perm::REQUESTS_FILES_VIEW_OWN, Perm::REQUESTS_FILES_VIEW_ALL],
            'show' => [Perm::REQUESTS_FILES_DOWNLOAD_OWN, Perm::REQUESTS_FILES_DOWNLOAD_ALL],
            'store' => Perm::REQUESTS_FILES_CREATE,
            'update' => [Perm::REQUESTS_FILES_EDIT_OWN, Perm::REQUESTS_FILES_EDIT_ALL],
            'destroy' => [Perm::REQUESTS_FILES_DELETE_OWN, Perm::REQUESTS_FILES_DELETE_ALL],
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @param  int  $requestId
     * @return JsonResponse
     */
    public function index(int $requestId): JsonResponse
    {
        $query = $this->request->files();

        if (! $this->user->perm(Perm::REQUESTS_FILES_VIEW_ALL)) {
            $query->where('user_id', $this->user->id);
        }

        $requestFiles = $query->get();
        EJoin::dispatchAfterResponse($requestId, ...$requestFiles);

        return response()->json([
            'message' => __('app.files.files_get'),
            'request_files' => $requestFiles,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  FileRequest  $request
     * @param  int  $requestId
     * @return JsonResponse
     */
    public function store(FileRequest $request, int $requestId): JsonResponse
    {
        $requestFiles = $request->file('files');

        $filesHelper = new FilesHelper($requestFiles);
        $filesHelper->upload('requests/'.$requestId);

        $uploadedIds = $filesHelper->getUploadedIds();
        $this->request->files()->attach($uploadedIds);
        $uploadedFiles = $this->request->files()->whereIn('files.id', $uploadedIds)->get();

        if (count($uploadedFiles)) {
            ECreate::dispatchAfterResponse($requestId, $uploadedFiles, $this->user->id);
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
     * Display the specified resource.
     *
     * @param  int  $requestId
     * @param  int  $fileId
     * @return JsonResponse
     */
    public function show(int $requestId, int $fileId): JsonResponse
    {
        $requestFile = $this->request->files()->findOrFail($fileId);

        // Download only own file
        if (! $this->user->perm(Perm::REQUESTS_FILES_DOWNLOAD_ALL) &&
            Gate::denies('owner', $requestFile)
        ) {
            return $this->responseNoPermission();
        }

        if (! Storage::exists($requestFile->path)) {
            return response()->json(['message' => __('app.files.file_not_found')], 422);
        }

        return Storage::download($requestFile->path, $requestFile->name.'.'.$requestFile->ext);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  FileRequest  $request
     * @param  int  $requestId
     * @param  int  $fileId
     * @return JsonResponse
     */
    public function update(FileRequest $request, int $requestId, int $fileId): JsonResponse
    {
        $requestFile = $this->request->files()->findOrFail($fileId);

        // Edit only own file
        if (! $this->user->perm(Perm::REQUESTS_FILES_EDIT_ALL) &&
            Gate::denies('owner', $requestFile)
        ) {
            return $this->responseNoPermission();
        }

        $requestFile->name = $request->name;

        if (! $requestFile->save()) {
            return $this->responseDatabaseSaveError();
        }

        EUpdate::dispatchAfterResponse($requestId, $fileId, $requestFile);

        return response()->json([
            'message' => __('app.files.file_updated'),
            'request_file' => $requestFile,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $requestId
     * @param  int  $fileId
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy(int $requestId, int $fileId): JsonResponse
    {
        $requestFile = $this->request->files()->findOrFail($fileId);

        // Delete only own file
        if (! $this->user->perm(Perm::REQUESTS_FILES_DELETE_ALL) &&
            Gate::denies('owner', $requestFile)
        ) {
            return $this->responseNoPermission();
        }

        if (! $requestFile->delete()) {
            return $this->responseDatabaseDestroyError();
        }

        EDelete::dispatchAfterResponse($requestId, $requestFile);

        return response()->json([
            'message' => __('app.files.file_destroyed'),
        ]);
    }
}
