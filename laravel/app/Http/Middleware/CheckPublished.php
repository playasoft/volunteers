<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use App\Models\Slot;

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
        $event = $request->{$model_name};
        if($request->{$model_name} === 'department') {
            $event = $request->{$model_name}->event;
        }
        if($request->{$model_name} === 'shift') {
            $event = $request->{$model_name}->event;
        }
        if($request->{$model_name} === 'schedule') {
            $event = $request->{$model_name}->shift->event;
        }
        if($request->{$model_name} === 'slot') {
            $event = $request->{$model_name}->schedule->shift->event;
        }

        $is_published = ($event->published_at !== null);
        $is_admin= $this->auth->user()->hasRole('admin');
        $is_department_lead = $this->auth->user()->hasRole('department-lead');
        if(!$is_published && !$is_admin && !$is_department_lead)
        {
            return response('Unauthorized.', 401);
        }
        return $next($request);
    }
}
