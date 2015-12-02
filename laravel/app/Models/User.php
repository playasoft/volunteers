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

    // Users can have slots
    public function slots()
    {
        return $this->hasMany('App\Models\Slot');
    }
}
