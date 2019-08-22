<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Models\Event;
use App\Models\Slot;

/**
 * 
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
        $event = Event::find($id);
        $roles = $event->shifts()->select('id', 'department_id', 'name')->get();

        return response()->json($roles->toArray());
    }

    /**
     * 
     */
    public function shifts(Request $request, $id)
    {
        $event = Event::find($id);
        $shifts = DB::table('slots')
            ->leftJoin('schedule', 'slots.schedule_id', '=', 'schedule.id')
            ->leftJoin('departments', 'schedule.department_id', '=', 'departments.id')
            ->leftJoin('events', 'departments.event_id', '=', 'events.id')
            ->leftJoin('users', 'slots.user_id', '=', 'users.id')
            ->leftJoin('user_data', 'user_data.user_id', '=', 'users.id')
            ->whereNotNull('slots.user_id')
            ->select(
                'slots.id', 
                'schedule.department_id',
                'schedule.shift_id',
                'schedule.start_date',
                'schedule.end_date',
                'schedule.start_time',
                'schedule.end_time',
                'slots.user_id',
                'users.email',
                'user_data.full_name',
                'slots.status'
            )->get();

        $shifts->each(function($shift) {
            $shift->role_id = $shift->shift_id;
            unset($shift->shift_id);
        });

        return response()->json($shifts->toArray());
    }

    /**
     * 
     */
    public function updateShift(Request $request, $id)
    {
        $slot = Slot::find($id);
        $slot->status = $request->get('status');
        $slot->save();
    }
}
