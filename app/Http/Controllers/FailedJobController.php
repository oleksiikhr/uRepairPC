<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\Perm;
use App\Models\FailedJob;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\FailedJobRequest;
use Illuminate\Support\Facades\Artisan;

class FailedJobController extends Controller
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
            'destroy' => Perm::JOBS_DELETE_FAILED_QUEUE,
            'destroyAll' => Perm::JOBS_DELETE_FAILED_QUEUE,
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @param  FailedJobRequest  $request
     * @return JsonResponse
     */
    public function index(FailedJobRequest $request): JsonResponse
    {
        $query = FailedJob::query();

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

        return response()->json($list);
    }

    /**
     * Display the specified resource.
     *
     * @param  FailedJob  $job
     * @return JsonResponse
     */
    public function show(FailedJob $job): JsonResponse
    {
        $job = FailedJob::findOrFail($job->id);

        return response()->json([
            'message' => __('app.jobs.show'),
            'failed_job' => $job,
        ]);
    }

    /**
     * Restart the failed queue.
     *
     * @return JsonResponse
     */
    public function retry(): JsonResponse
    {
        Artisan::call('queue:retry all');

        return response()->json([
            'message' => __('app.jobs.retry'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  FailedJob  $job
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy(FailedJob $job): JsonResponse
    {
        $job->delete();

        return response()->json([
            'message' => __('app.jobs.destroy'),
        ]);
    }

    /**
     * Remove resource from storage.
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroyAll(): JsonResponse
    {
        $count = FailedJob::query()->delete();

        return response()->json([
            'message' => __('app.jobs.destroy_all').': '.$count,
        ]);
    }
}
