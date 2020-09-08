<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\Perm;
use App\Models\RequestComment;
use Illuminate\Http\JsonResponse;
use App\Models\Request as RequestModel;
use App\Realtime\RequestComments\EJoin;
use App\Realtime\RequestComments\ECreate;
use App\Realtime\RequestComments\EDelete;
use App\Realtime\RequestComments\EUpdate;
use App\Http\Requests\RequestCommentRequest;
use Illuminate\Auth\Access\AuthorizationException;

class RequestCommentController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function permissions(): array
    {
        return [
            'index' => Perm::REQUESTS_COMMENTS_VIEW_ALL,
            'show' => Perm::REQUESTS_COMMENTS_VIEW_ALL,
            'store' => Perm::REQUESTS_COMMENTS_CREATE,
            'update' => [Perm::REQUESTS_COMMENTS_EDIT_OWN, Perm::REQUESTS_COMMENTS_EDIT_ALL],
            'destroy' => [Perm::REQUESTS_COMMENTS_DELETE_OWN, Perm::REQUESTS_COMMENTS_DELETE_ALL],
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @param  RequestModel  $requestModel
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function index(RequestModel $requestModel): JsonResponse
    {
        $this->authorize('show', $requestModel);

        $comments = $requestModel->comments()->get();
        EJoin::dispatchAfterResponse($requestModel->id, ...$comments);

        return response()->json([
            'message' => __('app.request_comments.index'),
            'request_comments' => $comments,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  RequestCommentRequest  $request
     * @param  RequestModel  $requestModel
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function store(RequestCommentRequest $request, RequestModel $requestModel): JsonResponse
    {
        $this->authorize('show', $requestModel);

        $comment = new RequestComment($request->validated());
        $comment->request_id = $requestModel->id;
        $comment->user_id = auth()->id();
        $comment->save();

        $comment = $requestModel->comments()->findOrFail($comment->id);
        ECreate::dispatchAfterResponse($requestModel->id, $comment);

        return response()->json([
            'message' => __('app.request_comments.store'),
            'request_comment' => $comment,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  RequestModel  $requestModel
     * @param  int  $id
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function show(RequestModel $requestModel, int $id): JsonResponse
    {
        $this->authorize('show', $requestModel);

        $comment = $requestModel->comments()->findOrFail($id);
        EJoin::dispatchAfterResponse($requestModel->id, $comment);

        return response()->json([
            'message' => __('app.request_comments.show'),
            'request_comment' => $comment,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  RequestCommentRequest  $request
     * @param  RequestModel  $requestModel
     * @param  int  $id
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function update(RequestCommentRequest $request, RequestModel $requestModel, int $id): JsonResponse
    {
        $this->authorize('show', $requestModel);

        $comment = $requestModel->comments()->findOrFail($id);

        $this->authorize('update', $comment);

        $comment->fill($request->validated());
        $comment->save();

        $comment = $requestModel->comments()->findOrFail($comment->id);
        EUpdate::dispatchAfterResponse($requestModel->id, $id, $comment);

        return response()->json([
            'message' => __('app.request_comments.update'),
            'request_comment' => $comment,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  RequestModel  $requestModel
     * @param  int  $id
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy(RequestModel $requestModel, int $id): JsonResponse
    {
        $this->authorize('show', $requestModel);

        $comment = $requestModel->comments()->findOrFail($id);

        $this->authorize('delete', $comment);

        $comment->delete();

        EDelete::dispatchAfterResponse($requestModel->id, $comment);

        return response()->json([
            'message' => __('app.request_comments.destroy'),
        ]);
    }
}
