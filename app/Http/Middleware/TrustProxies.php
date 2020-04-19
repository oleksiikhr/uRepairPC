<?php declare(strict_types=1);

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Fideloper\Proxy\TrustProxies as Middleware;

class TrustProxies extends Middleware
{
    /**
     * @inheritDoc
     */
    protected $proxies;

    /**
     * @inheritDoc
     */
    protected $headers = Request::HEADER_X_FORWARDED_ALL;
}
