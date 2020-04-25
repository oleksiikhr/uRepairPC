<?php declare(strict_types=1);

namespace App\Observers;

use App\Request;
use App\Events\Requests\EDelete;

class RequestObserver
{
    /**
     * Handle the request "deleted" event.
     *
     * @param  Request  $request
     * @return void
     */
    public function deleted(Request $request): void
    {
        EDelete::dispatchAfterResponse($request);
    }
}
