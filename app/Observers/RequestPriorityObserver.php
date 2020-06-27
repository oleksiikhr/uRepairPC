<?php declare(strict_types=1);

namespace App\Observers;

use App\Models\RequestPriority;
use App\Realtime\RequestPriorities\EDelete;

class RequestPriorityObserver
{
    /**
     * Handle the request priority "deleted" event
     *
     * @param  RequestPriority  $requestPriority
     * @return void
     */
    public function deleted(RequestPriority $requestPriority): void
    {
        EDelete::dispatchAfterResponse($requestPriority);
    }
}
