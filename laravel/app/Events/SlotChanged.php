<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

use App\Models\Slot;

class SlotChanged extends Event implements ShouldBroadcast
{
    use SerializesModels;

    public $slot;
    public $change;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Slot $slot, $change)
    {
        $this->slot = $slot;
        $this->change = $change;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return ['event-' . $this->slot->event->id];
    }

    /**
     * Get the broadcast event name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'slot-changed';
    }
}
