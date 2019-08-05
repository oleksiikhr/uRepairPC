<?php

namespace App\Observers;

use App\RequestType;
use App\Events\RequestTypes\EDelete;

class RequestTypeObserver
{
    /**
     * Handle the request type "deleted" event.
     *
     * @param  \App\RequestType  $requestType
     * @return void
     */
    public function deleted(RequestType $requestType)
    {
        event(new EDelete($requestType));
    }
}
