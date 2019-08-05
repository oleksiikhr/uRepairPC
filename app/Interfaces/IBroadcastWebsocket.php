<?php

namespace App\Interfaces;

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
     * @return array|string|null
     */
    public function rooms();

    /**
     * @return array|null
     */
    public function params(): ?array;

    /**
     * @return mixed
     */
    public function data();
}
