<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EntityTriggerEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $action;

    public array $data;

    public ?int $id;

    /**
     * Create a new event instance.
     */
    public function __construct(string $action, array $data, ?int $id = null)
    {
        $this->action = $action;
        $this->data = $data;
        $this->id = $id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        $channels = [new Channel('entity-triggers')];

        if ($this->id) {
            $channels[] = new Channel("entity-triggers.{$this->id}");
        }

        return $channels;
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'action' => $this->action,
            'data' => $this->data,
            'id' => $this->id,
            'timestamp' => now()->toIso8601String(),
        ];
    }
}
