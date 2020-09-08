<?php

declare(strict_types=1);

namespace App\Contracts;

interface IBroadcastWebsocket
{
    /**
     * @return string
     */
    public function event(): string;

    /**
     * @return string
     */
    public function type(): string;

    /**
     * @return array
     */
    public function rooms(): array;

    /**
     * @return array
     */
    public function params(): array;

    /**
     * @return array
     */
    public function join(): array;

    /**
     * @return mixed
     */
    public function data();
}
