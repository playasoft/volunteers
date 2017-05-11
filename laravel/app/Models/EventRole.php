<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Role;

class EventRole extends Model
{
    protected $fillable = ['role_id', 'event_id', 'foreign_id', 'foreign_type'];

    // Event roles belong to a role
    public function role()
    {
        return $this->belongsTo('App\Models\Role');
    }

    // Event roles belong to a event
    public function event()
    {
        return $this->belongsTo('App\Models\Event');
    }

    // Event roles might be associated with a foreign table
    public function foreign()
    {
        return $this->morphTo();
    }

    // Helper function to sync the roles that belong to a foreign relationship
    static function syncForeign($event, $foreign_type, $foreign_id, $roles)
    {
        // Start by removing all roles for this relationship
        EventRole::where('event_id', $event->id)->where('foreign_type', $foreign_type)->where('foreign_id', $foreign_id)->delete();

        if(isset($roles))
        {
            // Loop through array of role names
            foreach($roles as $roleName)
            {
                // Figure out what role this name belongs to
                $role = Role::where('name', $roleName)->first();

                if(!empty($role))
                {
                    // Create a new event role
                    $roleData =
                    [
                        'role_id' => $role->id,
                        'event_id' => $event->id,
                        'foreign_id' => $foreign_id,
                        'foreign_type' => $foreign_type
                    ];

                    try
                    {
                        EventRole::create($roleData);
                    }
                    catch(\Exception $exception)
                    {
                        // Todo: Throw an error if the exception is anything but a duplicate key error?
                    }
                }
            }
        }
    }
}
