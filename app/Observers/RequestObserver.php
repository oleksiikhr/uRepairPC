<?php

declare(strict_types=1);

namespace App\Observers;

use App\Enums\Perm;
use App\Models\User;
use App\Models\Request;
use App\Mail\RequestCreated;
use App\Realtime\Requests\EDelete;
use Illuminate\Support\Facades\Mail;

class RequestObserver
{
    private const CHUNK_LENGTH = 1000;

    /**
     * Handle the user "created" event.
     *
     * @param  Request  $request
     * @return void
     */
    public function created(Request $request): void
    {
        User::filterRoles([Perm::EQUIPMENTS_EDIT_ALL])
            ->chunk(self::CHUNK_LENGTH, static function ($users) use ($request) {
                foreach ($users as $user) {
                    Mail::to($user)->queue(new RequestCreated($request));
                }
            });
    }

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
