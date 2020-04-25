<?php declare(strict_types=1);

namespace App\Events\Common;

use Illuminate\Support\Facades\Event;
use Illuminate\Queue\SerializesModels;
use App\Contracts\IBroadcastWebsocket;

abstract class EBroadcast extends Event implements IBroadcastWebsocket
{
    use SerializesModels;

    const TYPE_JOIN = 'join';
    const TYPE_SYNC = 'sync';
    const TYPE_CREATE = 'create';
    const TYPE_UPDATE = 'update';
    const TYPE_DELETE = 'delete';

    public function __destruct()
    {
        \Amqp::publish('', json_encode([
            'socketId' => request()->header('X-Socket-ID'),
            'event' => 'server.'.$this->event(),
            'rooms' => $this->rooms(),
            'params' => $this->params(),
            'data' => $this->transformData($this->data()),
            'join' => $this->join(), // Only for CREATE event
            'type' => $this->type(),
        ]), ['queue' => 'server.changes']);
    }

    /**
     * Join to this room after broadcast.
     *
     * @return array
     */
    public function join(): array
    {
        return [];
    }

    /**
     * @param  mixed  $data
     * @return mixed
     */
    protected function transformData($data)
    {
        if ($data && method_exists($data, 'toArray')) {
            return $data->toArray();
        }

        return $data;
    }
}
