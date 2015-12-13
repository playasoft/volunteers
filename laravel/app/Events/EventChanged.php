<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

use App\Models\Event as EventModel;

class EventChanged extends Event implements ShouldBroadcast
{
    use SerializesModels;

    public $event;
    public $change;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(EventModel $event, $change)
    {
        $this->event = $event;
        $this->change = $change;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return ['event-' . $this->event->id];
    }

    /**
     * Get the broadcast event name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'event-changed';
    }
}
