<?php declare(strict_types=1);

namespace App\Observers;

use App\Models\RequestType;
use App\Realtime\RequestTypes\EDelete;

class RequestTypeObserver
{
    /**
     * Handle the request type "deleted" event
     *
     * @param  RequestType  $requestType
     * @return void
     */
    public function deleted(RequestType $requestType): void
    {
        EDelete::dispatch($requestType);
    }
}
