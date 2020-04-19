<?php declare(strict_types=1);

namespace App\Observers;

use App\RequestType;
use App\Events\RequestTypes\EDelete;

class RequestTypeObserver
{
    /**
     * Handle the request type "deleted" event.
     *
     * @param  RequestType  $requestType
     * @return void
     */
    public function deleted(RequestType $requestType): void
    {
        event(new EDelete($requestType));
    }
}
