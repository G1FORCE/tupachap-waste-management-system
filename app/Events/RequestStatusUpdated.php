<?php

namespace App\Events;

use App\Models\WasteRequest;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Fired whenever a request's status changes (pending -> matched -> en_route
 * -> collected -> paid). The user's dashboard listens for this and updates
 * the status badge / notification live without polling the server.
 */
class RequestStatusUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public WasteRequest $request) {}

    public function broadcastOn(): array
    {
        return [new Channel('request.' . $this->request->id)];
    }

    public function broadcastAs(): string
    {
        return 'status.updated';
    }

    public function broadcastWith(): array
    {
        return [
            'status' => $this->request->status,
            'collector_id' => $this->request->collector_id,
        ];
    }
}
