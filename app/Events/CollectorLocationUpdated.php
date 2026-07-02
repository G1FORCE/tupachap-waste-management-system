<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Fired every time a collector's GPS ping is saved.
 * The frontend (Leaflet map) listens on the private channel for the
 * specific request and moves the collector's marker live, no page refresh.
 */
class CollectorLocationUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public int $collectorId,
        public float $lat,
        public float $lng,
        public ?int $requestId = null,
    ) {}

    public function broadcastOn(): array
    {
        // Broadcasting on the specific request channel keeps this private -
        // only the user + collector involved in that job can see the location.
        return [new Channel('request.' . $this->requestId)];
    }

    public function broadcastAs(): string
    {
        return 'location.updated';
    }
}
