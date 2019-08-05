<?php

namespace App\Observers;

use App\Request;
use App\Events\Requests\EDelete;

class RequestObserver
{
    /**
     * Handle the request "deleted" event.
     *
     * @param  \App\Request  $request
     * @return void
     */
    public function deleted(Request $request)
    {
        event(new EDelete($request));
    }
}
