<?php declare(strict_types=1);

namespace App\Observers;

use App\RequestPriority;
use App\Events\RequestPriorities\EDelete;

class RequestPriorityObserver
{
    /**
     * Handle the request priority "deleted" event.
     *
     * @param  RequestPriority  $requestPriority
     * @return void
     */
    public function deleted(RequestPriority $requestPriority): void
    {
        event(new EDelete($requestPriority));
    }
}
