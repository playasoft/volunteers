<?php

namespace App\Listeners;

use App\Events\FileChanged;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;

class SendUserFileChanged
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  FileChanged  $event
     * @return void
     */
    public function handle(FileChanged $event)
    {
        $file = $event->file;
        $user = $event->file->user;

        if($file->status == 'approved')
        {
            Mail::send('emails/user-file-approved', compact('file', 'user'), function ($message) use ($user)
            {
                $message->to($user->email, $user->name)->subject('Uploaded File Approved');
            });
        }
        elseif($file->status == 'denied')
        {
            Mail::send('emails/user-file-denied', compact('file', 'user'), function ($message) use ($user)
            {
                $message->to($user->email, $user->name)->subject('Uploaded File Denied');
            });
        }
    }
}
