<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Event;

/**
 * TODO: add auth bullshit
 */
class APIController extends Controller
{
    /**
     * 
     */
    public function profile(Request $request)
    {
        $user = Auth::user();
        return response()->json([
            'username' => $user->name,
            'email' => $user->email,
            'permissions' => $user->getRoleNames(),
            'full_name' => $user->data->full_name ?? null,
            'burner_name' => $user->data->burner_name ?? null,
            'phone_number' => $user->data->phone ?? null,
        ]);
    }

    /**
     * 
     */
    public function events(Request $request)
    {
        $events = Event::select('id', 'name', 'start_date', 'end_date')->get();

        return response()->json($events->toArray());
    }

    /**
     * 
     */
    public function departments(Request $request, $id)
    {
        $event = Event::find($id);
        $departments = $event->departments()->select('id', 'name')->get();

        return response()->json($departments->toArray());
    }

    /**
     * 
     */
    public function roles(Request $request, $id)
    {
        // TODO

        $event = Event::find($id);
        $roles = $event->shifts()->select('id', 'department_id', 'name')->get();

        return response()->json($roles->toArray());
    }

    /**
     * 
     */
    public function shifts(Request $request, $id)
    {
        // TODO

        $event = Event::find($id);
        $data = DB::table('slots')
            ->leftJoin('schedule', 'slots.schedule_id', '=', 'schedule.id')
            ->leftJoin('departments', 'schedule.department_id', '=', 'departments.id')
            ->leftJoin('events', 'departments.event_id', '=', 'events.id')
            ->leftJoin('users', 'slots.user_id', '=', 'users.id')
            ->leftJoin('user_data', 'user_data.user_id', '=', 'users.id')
            ->leftJoin('event_roles', 'events.id', '=', 'event_roles.id')
            ->leftJoin('roles', 'event_roles.role_id', '=', 'roles.id')
            ->whereNotNull('slots.user_id')
            ->select(
                'events.id', 
                'schedule.department_id',
                // 'schedule.id', //replace with role_id
                'slots.start_time',
                'slots.end_time',
                'slots.user_id',
                'users.email',
                'user_data.full_name',
                'slots.status'
            )->get();

        return response()->json($data->toArray());
    }

}
