<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    protected $listen = [
        //
    ];

    /**
     * {@inheritdoc}
     */
    public function boot(): void
    {
        parent::boot();

        //
    }
}
