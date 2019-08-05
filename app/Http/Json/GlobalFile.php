<?php

namespace App\Http\Json;

class GlobalFile extends Json
{
    private const FILE_NAME = 'settings.json';

    public function __construct()
    {
        parent::__construct(self::FILE_NAME, 'global', 'public');
    }

    /**
     * Get attribute and type.
     *
     * @return mixed
     * @example
     *  ['attr' => 'string']
     */
    public function getAttributes()
    {
        return [
            'meta_title' => 'string',
            'app_name' => 'string',
            'logo_auth' => 'file',
            'logo_header' => 'file',
            'favicon' => 'file',
            'name_and_logo' => 'bool',
        ];
    }

    /**
     * Get default data.
     *
     * @return array
     */
    public function getDefaultData()
    {
        return [
            'meta_title' => config('app.name'),
            'app_name' => null,
            'logo_auth' => null,
            'logo_header' => null,
            'favicon' => null,
            'name_and_logo' => null,
        ];
    }
}
