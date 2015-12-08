<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;

use App\Models\User;

class UserRegistered extends Event
{
    use SerializesModels;

    public $user;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }
}
