<?php

namespace App\Http\Json;

class ManifestFile extends Json
{
    const FILE_NAME = 'manifest.json';

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
            'name' => 'string',
            'short_name' => 'string',
            'orientation' => 'string',
            'display' => 'string',
            'background_color' => 'string',
            'theme_color' => 'string',
            // icons custom upload
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
            'name' => config('app.name'),
            'short_name' => config('app.name'),
            'orientation' => 'portrait',
            'display' => 'standalone',
            'background_color' => '#ffffff',
            'theme_color' => '#409eff',
            'icons' => [
                (object) [
                    'src' => 'images/icon_96x96.png',
                    'sizes' => '96x96',
                    'type' => 'image/png',
                ],
                (object) [
                    'src' => 'images/icon_128x128.png',
                    'sizes' => '128x128',
                    'type' => 'image/png',
                ],
                (object) [
                    'src' => 'images/icon_192x192.png',
                    'sizes' => '192x192',
                    'type' => 'image/png',
                ],
                (object) [
                    'src' => 'images/icon_256x256.png',
                    'sizes' => '256x256',
                    'type' => 'image/png',
                ],
                (object) [
                    'src' => 'images/icon_384x384.png',
                    'sizes' => '384x384',
                    'type' => 'image/png',
                ],
                (object) [
                    'src' => 'images/icon_512x512.png',
                    'sizes' => '512x512',
                    'type' => 'image/png',
                ],
            ],
        ];
    }
}
