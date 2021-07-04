<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use App\Models\Slot;
use App\Models\Department;
use App\Models\Schedule;
use App\Models\Shift;

class CheckPublished
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param  Guard  $auth
     * @return void
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $model_name)
    {
        if($request->{$model_name}) 
        {
            $event = $this->childModelToEvent($request->{$model_name});

            $is_published = ($event->published_at !== null);
            $is_admin= $this->auth->user()->hasRole('admin');
            $is_department_lead = $this->auth->user()->hasRole('department-lead');
            if(!$is_published && !$is_admin && !$is_department_lead)
            {
                return response('Unauthorized.', 401);
            }
        }
        
        return $next($request);
    }

    /**
     * Get parent event of shift, slot, schedule, or department
     *
     * @param   Model   $model  descendent model of Event
     * @return  Event           Event or parent Event
     */
    public static function childModelToEvent($model)
    {
        
        $model_class = get_class($model);
        $event = $model;
        if($model_class === Department::class) {
            $event = $model->event;
        }
        if($model_class === Shift::class) {
            $event = $model->event;
        }
        if($model_class === Schedule::class) {
            $event = $model->shift->event;
        }
        if($model_class === Slot::class) {
            $event = $model->schedule->shift->event;
        }
        return $event;
    }
}
