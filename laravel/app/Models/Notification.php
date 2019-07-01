<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Mail;

class Notification extends Model
{
    public function from()
    {
        return $this->belongsTo('App\Models\User', 'user_from');
    }

    public function to()
    {
        return $this->belongsTo('App\Models\User', 'user_to');
    }

    public static function send(User $user_to, $type, $metadata, User $user_from = null)
    {
        // Email template variables
        $templateVars = ['user' => $user_to];

        // Check if any template variables were set in the metadata
        if(isset($metadata['template-vars']))
        {
            // Prevent template vars from being saved in the database
            $templateVars = array_merge($templateVars, $metadata['template-vars']);
            unset($metadata['template-vars']);
        }

        $notification = new Notification;
        $notification->type = $type;
        $notification->status = 'new';
        $notification->metadata = json_encode($metadata);
        $notification->user_to = $user_to->id;
        $notification->user_from = $user_from ? $user_from->id : null;
        $notification->save();

        if($type == 'email')
        {
            Mail::send($metadata['template'], $templateVars, function ($message) use ($user_to, $metadata)
            {
                $message->to($user_to->email, $user_to->name)->subject($metadata['subject']);
            });

            $notification->status = 'sent';
            $notification->save();
        }

        return $notification;
    }

    public static function queue(User $user_to, $type, $metadata, User $user_from = null)
    {
        $notification = new Notification;
        $notification->type = $type;
        $notification->status = 'new';
        $notification->metadata = json_encode($metadata);
        $notification->user_to = $user_to->id;
        $notification->user_from = $user_from ? $user_from->id : null;
        $notification->save();

        return $notification;
    }
}
