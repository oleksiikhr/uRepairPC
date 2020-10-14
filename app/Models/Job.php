<?php

declare(strict_types=1);

namespace App\Models;

class Job extends BaseModel
{
    /**
     * {@inheritDoc}
     */
    protected $perPage = 15;

    /**
     * @var array
     */
    public const ALLOW_COLUMNS_SEARCH = [
        'id',
        'queue',
        'reserved_at',
        'updated_at',
        'created_at',
    ];

    /**
     * @var array
     */
    public const ALLOW_COLUMNS_SORT = [
        'id',
        'queue',
        'reserved_at',
        'updated_at',
        'created_at',
    ];

    /**
     * {@inheritdoc}
     */
    protected $table = 'jobs';
}
