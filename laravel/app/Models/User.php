<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    protected $fillable = ['name', 'email', 'password'];
    protected $dates = ['created_at', 'updated_at', 'reset_time'];

    // Users have roles
    public function roles()
    {
        return $this->hasMany('App\Models\UserRole');
    }

    // Users can have user data
    public function data()
    {
        return $this->hasOne('App\Models\UserData');
    }

    // Users can have uploads
    public function uploads()
    {
        return $this->hasMany('App\Models\UserUpload');
    }

    // Users can sign up for slots
    public function slots()
    {
        return $this->hasMany('App\Models\Slot');
    }
}
