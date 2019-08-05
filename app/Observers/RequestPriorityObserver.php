<?php

namespace App\Observers;

use App\RequestPriority;
use App\Events\RequestPriorities\EDelete;

class RequestPriorityObserver
{
    /**
     * Handle the request priority "deleted" event.
     *
     * @param  \App\RequestPriority  $requestPriority
     * @return void
     */
    public function deleted(RequestPriority $requestPriority)
    {
        event(new EDelete($requestPriority));
    }
}
