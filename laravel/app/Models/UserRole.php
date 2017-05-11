<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Role;

class UserRole extends Model
{
    protected $fillable = ['role_id', 'user_id', 'foreign_id', 'foreign_type'];

    // User roles belong to a role
    public function role()
    {
        return $this->belongsTo('App\Models\Role');
    }

    // User roles belong to a user
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    // User roles might be associated with a foreign table
    public function foreign()
    {
        return $this->morphTo();
    }

    // Helper function to add roles to a user
    public static function assign($user, $roleNames = [], $options = [])
    {
        // Convert string argument to array
        if(is_string($roleNames))
        {
            $roleNames = [$roleNames];
        }

        // Loop through all roles passed
        foreach($roleNames as $roleName)
        {
            $role = Role::where('name', $roleName)->first();

            if(!empty($role))
            {
                // Create a new event role
                $roleData =
                [
                    'role_id' => $role->id,
                    'user_id' => $user->id,
                ];

                if(isset($options['foreign_id']) && isset($options['foreign_type']))
                {
                    $roleData['foreign_id'] = $options['foreign_id'];
                    $roleData['foreign_type'] = $options['foreign_type'];
                }

                try
                {
                    UserRole::create($roleData);
                }
                catch(\Exception $exception)
                {
                    // Todo: Throw an error if the exception is anything but a duplicate key error?
                }
            }
        }
    }

    // Helper function to clear roles assigned to a user
    public static function clear($user)
    {
        UserRole::where('user_id', $user->id)->delete();
    }
}
