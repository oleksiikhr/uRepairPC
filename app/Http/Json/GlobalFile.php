<?php declare(strict_types=1);

namespace App\Http\Json;

class GlobalFile extends Json
{
    private const FILE_NAME = 'settings.json';

    public function __construct()
    {
        parent::__construct(self::FILE_NAME, 'global', 'public');
    }

    /**
     * @inheritDoc
     */
    public function getAttributes(): array
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
     * @inheritDoc
     */
    public function getDefaultData(): array
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
