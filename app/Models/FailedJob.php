<?php

declare(strict_types=1);

namespace App\Models;

class FailedJob extends BaseModel
{
    /**
     * {@inheritDoc}
     */
    protected $perPage = 15;

    /**
     * {@inheritDoc}
     */
    public $timestamps = false;

    /**
     * @var array
     */
    public const ALLOW_COLUMNS_SEARCH = [
        'id',
        'uuid',
        'connection',
        'queue',
        'failed_at',
        'created_at',
    ];

    /**
     * @var array
     */
    public const ALLOW_COLUMNS_SORT = [
        'id',
        'uuid',
        'connection',
        'queue',
        'failed_at',
        'created_at',
    ];

    /**
     * {@inheritdoc}
     */
    protected $table = 'failed_jobs';
}
