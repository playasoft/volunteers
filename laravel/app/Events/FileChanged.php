<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

use App\Models\UserUpload;

class FileChanged extends Event
{
    use SerializesModels;

    public $file;
    public $change;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(UserUpload $file, $change)
    {
        $this->file = $file;
        $this->change = $change;
    }
}
