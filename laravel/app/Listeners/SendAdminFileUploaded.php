<?php

namespace App\Listeners;

use App\Events\FileUploaded;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use Mail;
use App\Models\User;

class SendAdminFileUploaded
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
     * @param  FileUploaded  $event
     * @return void
     */
    public function handle(FileUploaded $event)
    {
        $file = $event->file;
        $admins = User::where('role', 'admin')->get();

        foreach($admins as $admin)
        {
            Mail::send('emails/admin-file-uploaded', compact('file'), function ($message) use ($admin)
            {
                $message->to($admin->email, $admin->name)->subject('New file uploaded!');
            });
        }
    }
}
