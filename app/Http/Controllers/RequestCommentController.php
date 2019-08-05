<?php

namespace App\Http\Controllers;

use App\User;
use App\Enums\Perm;
use App\RequestComment;
use Illuminate\Http\Request;
use App\Request as RequestModel;
use Illuminate\Support\Facades\Gate;
use App\Events\RequestComments\EJoin;
use App\Events\RequestComments\ECreate;
use App\Events\RequestComments\EDelete;
use App\Events\RequestComments\EUpdate;
use App\Http\Requests\RequestCommentRequest;

class RequestCommentController extends Controller
{
    /**
     * @var RequestModel
     */
    private $_request;

    /**
     * @var User
     */
    private $_user;

    /**
     * Add middleware depends on user permissions.
     *
     * @param Request $request
     * @return array
     */
    public function permissions(Request $request): array
    {
        $this->_user = auth()->user();

        if (! $this->_user) {
            $this->middleware('jwt.auth');

            return [];
        }

        $requestId = (int) $request->route('request');
        $this->_request = RequestModel::findOrFail($requestId);

        // Permissions on request before get a comments
        if (! RequestModel::hasAccessByPerm($this->_request, $this->_user)) {
            $this->middleware('permission:disable');

            return [];
        }

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
     * @param  int  $requestId
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(int $requestId)
    {
        $requestComments = $this->_request->comments()->get();
        event(new EJoin($requestId, ...$requestComments));

        return response()->json([
            'message' => __('app.request_comments.index'),
            'request_comments' => $requestComments,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  RequestCommentRequest  $request
     * @param  int  $requestId
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(RequestCommentRequest $request, int $requestId)
    {
        $requestComment = new RequestComment;
        $requestComment->fill($request->all());
        $requestComment->request_id = $requestId;
        $requestComment->user_id = $this->_user->id;

        if (! $requestComment->save()) {
            return $this->responseDatabaseSaveError();
        }

        $requestComment = $this->_request->comments()->findOrFail($requestComment->id);
        event(new ECreate($requestId, $requestComment));

        return response()->json([
            'message' => __('app.request_comments.store'),
            'request_comment' => $requestComment,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $requestId
     * @param  int  $commentId
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $requestId, int $commentId)
    {
        $requestComment = $this->_request->comments()->findOrFail($commentId);

        event(new EJoin($requestId, $requestComment));

        return response()->json([
            'message' => __('app.request_comments.show'),
            'request_comment' => $requestComment,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  RequestCommentRequest  $request
     * @param  int  $requestId
     * @param  int  $commentId
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(RequestCommentRequest $request, int $requestId, int $commentId)
    {
        $requestComment = $this->_request->comments()->findOrFail($commentId);

        // Show only own comment
        if (! $this->_user->perm(Perm::REQUESTS_COMMENTS_EDIT_ALL) &&
            Gate::denies('owner', $requestComment)
        ) {
            return $this->responseNoPermission();
        }

        $requestComment->fill($request->all());

        if (! $requestComment->save()) {
            return $this->responseDatabaseSaveError();
        }

        $requestComment = $this->_request->comments()->findOrFail($requestComment->id);
        event(new EUpdate($requestId, $commentId, $requestComment));

        return response()->json([
            'message' => __('app.request_comments.update'),
            'request_comment' => $requestComment,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $requestId
     * @param  int  $commentId
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(int $requestId, int $commentId)
    {
        $requestComment = $this->_request->comments()->findOrFail($commentId);

        // Delete only own file
        if (! $this->_user->perm(Perm::REQUESTS_COMMENTS_DELETE_ALL) &&
            Gate::denies('owner', $requestComment)
        ) {
            return $this->responseNoPermission();
        }

        if (! $requestComment->delete()) {
            return $this->responseDatabaseDestroyError();
        }

        event(new EDelete($requestId, $requestComment));

        return response()->json([
            'message' => __('app.request_comments.destroy'),
        ]);
    }
}
