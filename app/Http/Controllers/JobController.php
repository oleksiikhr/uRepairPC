<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\Perm;
use App\Models\Job;
use App\Realtime\Job\EJoin;
use App\Http\Requests\JobRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Auth\Access\AuthorizationException;

class JobController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function permissions(): array
    {
        return [
            'index' => Perm::JOBS_VIEW_ALL,
            'show' => Perm::JOBS_VIEW_ALL,
            'retry' => Perm::JOBS_RETRY,
            'destroy' => [Perm::JOBS_DELETE_ALL_QUEUE, Perm::JOBS_DELETE_FAILED_QUEUE],
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @param  JobRequest  $request
     * @return JsonResponse
     */
    public function index(JobRequest $request): JsonResponse
    {
        $query = Job::query();

        // Search
        if ($request->has('search') && $request->exists('columns')) {
            foreach ($request->columns as $column) {
                $query->orWhere($column, 'LIKE', $request->search.'%');
            }
        }

        // Order
        if ($request->has('sortColumn')) {
            $query->orderBy($request->sortColumn, $request->sortOrder === 'descending' ? 'desc' : 'asc');
        }

        $list = $query->paginate();
        EJoin::dispatchAfterResponse(...$list->items());

        return response()->json($list);
    }

    /**
     * Display the specified resource.
     *
     * @param  Job  $job
     * @return JsonResponse
     */
    public function show(Job $job): JsonResponse
    {
        $job = Job::findOrFail($job->id);

        EJoin::dispatchAfterResponse($job);

        return response()->json([
            'message' => __('app.jobs.show'),
            'job' => $job,
        ]);
    }

    /**
     * Restart the failed queue.
     *
     * @return JsonResponse
     */
    public function retry(): JsonResponse
    {
        $result = Artisan::call('queue:retry');

        return response()->json([
            'message' => __('app.jobs.retry').' '.$result,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Job  $job
     * @return JsonResponse
     * @throws AuthorizationException
     * @throws \Exception
     */
    public function destroy(Job $job): JsonResponse
    {
        $this->authorize('delete', $job);

        $job->delete();

        return response()->json([
            'message' => __('app.jobs.destroy'),
        ]);
    }
}
