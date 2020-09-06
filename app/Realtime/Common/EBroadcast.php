<?php declare(strict_types=1);

namespace App\Realtime\Common;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use App\Contracts\IBroadcastWebsocket;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

abstract class EBroadcast implements ShouldQueue, IBroadcastWebsocket
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public const TYPE_JOIN = 'join';
    public const TYPE_SYNC = 'sync';
    public const TYPE_CREATE = 'create';
    public const TYPE_UPDATE = 'update';
    public const TYPE_DELETE = 'delete';

    /**
     * @return void
     */
    public function handle(): void
    {
        \Amqp::publish('', json_encode([
            'socketId' => \request()->header('X-Socket-ID'),
            'event' => 'server.'.$this->event(),
            'rooms' => $this->rooms(),
            'params' => $this->params(),
            'data' => $this->transformData($this->data()),
            'join' => $this->join(), // Only for CREATE event
            'type' => $this->type(),
        ]), ['queue' => 'server.changes']);
    }

    /**
     * Join to this room after broadcast
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
