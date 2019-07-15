<?php

namespace App\Models;

use App\Cascade;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use SoftDeletes, Cascade;

    /**
     * Structure:
     *
     * @return mixed
     */
    protected static function cascadeUpdateRelationshipFields()
    {
        return [
            'published_at' => 'all',
        ];
    }

    /**
     * Structure:
     *
     * @return mixed
     */
    protected static function cascadeDeleteRelationships()
    {
        return 'all';
    }

    /**
     *
     */
    protected static function relationships()
    {
        return [
            'shifts',
            'schedule',
        ];
    }

    protected $fillable = ['name', 'description'];

    // Departments belong to an event
    public function event()
    {
        return $this->belongsTo('App\Models\Event');
    }

    // Departments have shifts
    public function shifts()
    {
        return $this->hasMany('App\Models\Shift');
    }

    // Departments have a schedule
    public function schedule()
    {
        return $this->hasMany('App\Models\Schedule');
    }

    // Departments have slots through the schedule
    public function slots()
    {
        return $this->hasManyThrough('App\Models\Slot', 'App\Models\Schedule');
    }
}
