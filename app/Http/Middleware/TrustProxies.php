<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Fideloper\Proxy\TrustProxies as Middleware;

class TrustProxies extends Middleware
{
    /**
     * {@inheritdoc}
     */
    protected $proxies;

    /**
     * {@inheritdoc}
     */
    protected $headers = Request::HEADER_X_FORWARDED_ALL;
}
