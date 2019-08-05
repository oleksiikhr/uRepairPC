<?php

namespace App\Observers;

use App\RequestStatus;
use App\Events\RequestStatuses\EDelete;

class RequestStatusObserver
{
    /**
     * Handle the request status "deleted" event.
     *
     * @param  \App\RequestStatus  $requestStatus
     * @return void
     */
    public function deleted(RequestStatus $requestStatus)
    {
        event(new EDelete($requestStatus));
    }
}
