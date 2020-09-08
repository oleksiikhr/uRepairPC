<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Contracts\IPermissions;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

abstract class Controller extends BaseController implements IPermissions
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct()
    {
        $this->allowPermissions($this->permissions());
    }

    /**
     * Register middleware on depends key-value array
     *  key - method
     *  value - permissions.
     *
     * @param  array  $methods
     * @return void
     */
    private function allowPermissions(array $methods): void
    {
        foreach ($methods as $method => $data) {
            $permissions = is_array($data) ? implode('|', $data) : $data;

            $this->middleware('permission:'.$permissions)->only($method);
        }
    }
}
