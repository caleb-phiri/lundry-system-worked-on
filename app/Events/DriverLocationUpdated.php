<?php

namespace App\Events;

use App\Models\DriverLocation;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DriverLocationUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $location;

    public function __construct(DriverLocation $location)
    {
        $this->location = $location;
    }

    public function broadcastOn()
    {
        return new Channel('drivers-locations');
    }

    public function broadcastAs()
    {
        return 'driver.location.updated';
    }

    public function broadcastWith()
    {
        return [
            'driver_id' => $this->location->driver_id,
            'latitude' => $this->location->latitude,
            'longitude' => $this->location->longitude,
            'last_updated' => $this->location->last_updated_at
        ];
    }
}