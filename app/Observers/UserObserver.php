<?php declare(strict_types=1);

namespace App\Observers;

use App\Models\User;
use App\Mail\UserCreated;
use App\Realtime\Users\ECreate;
use App\Realtime\Users\EDelete;
use App\Realtime\Users\EUpdate;
use Illuminate\Support\Facades\Mail;

class UserObserver
{
    /**
     * Handle the user "creating" event
     *
     * @param  User  $user
     * @return void
     */
    public function creating(User $user): void
    {
        $password = User::generateRandomStrPassword();

        $user->password = bcrypt($password);

        Mail::to($user)->send(new UserCreated($password));
    }

    /**
     * Handle the user "created" event
     *
     * @param  User  $user
     * @return void
     */
    public function created(User $user): void
    {
        ECreate::dispatchAfterResponse($user);
    }

    /**
     * Handle the user "updated" event
     *
     * @param  User  $user
     * @return void
     */
    public function updated(User $user): void
    {
        EUpdate::dispatchAfterResponse($user->id, $user->getChangesWithoutHiddenAttrs());
    }

    /**
     * Handle the user "deleted" event
     *
     * @param  User  $user
     * @return void
     */
    public function deleted(User $user): void
    {
        EDelete::dispatchAfterResponse($user);
    }
}
