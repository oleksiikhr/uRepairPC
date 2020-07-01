<?php declare(strict_types=1);

if (! function_exists('perm')) {
    /**
     * @param  array|string  $permission
     * @return bool
     */
    function perm($permission): bool
    {
        $user = auth()->user();

        return optional($user)->perm($permission) ?? false;
    }
}
