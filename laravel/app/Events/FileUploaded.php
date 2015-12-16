<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

use App\Models\UserUpload;

class FileUploaded extends Event
{
    use SerializesModels;

    public $file;
    
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(UserUpload $file)
    {
        $this->file = $file;
    }
}
