<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface IPermissions
{
    /**
     * Add middleware depends on user permissions.
     *
     * @param  Request  $request
     * @return array
     * @example [
     *  'index' => Permissions::EQUIPMENTS_FILES_VIEW,
     *  'show' => Permissions::EQUIPMENTS_FILES_DOWNLOAD,
     *  'store' => Permissions::EQUIPMENTS_FILES_CREATE,
     *  'update' => Permissions::EQUIPMENTS_FILES_EDIT,
     *  'destroy' => Permissions::EQUIPMENTS_FILES_DELETE,
     * ]
     */
    public function permissions(Request $request): array;
}
