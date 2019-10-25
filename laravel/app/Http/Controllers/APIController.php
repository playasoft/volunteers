<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Helpers;
use App\Models\Event;
use App\Models\Slot;
use App\Models\User;

/**
 * Controller for the API
 * Note: May need to split this into multiple controllers,
 * but for now this works.
 */
class APIController extends Controller
{
    /**
     * Returns the current user by: 
     * user name,
     * email,
     * full name,
     * burner name,
     * phone number,
     * and permissions they currently have
     *
     * @param   Request     $request    Incoming Request
     * @return  Response                JSON Response
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
     * Returns all current events by id, name, start date, and end date
     *
     * @param   Request     $request    Incoming Request
     * @return  Response                JSON Response
     */
    public function events(Request $request)
    {
        $events = Event::select('id AS event_id', 'name', 'start_date', 'end_date')->get();

        return response()->json($events->toArray());
    }

    /**
     * Returns all departments of an event by id and name
     *
     * @param   Request     $request    Incoming Request
     * @param   int         $id         Event ID
     * @return  Response                JSON Response
     */
    public function departments(Request $request, $id)
    {
        $event = Event::find($id);
        $departments = $event->departments()->select('id AS department_id', 'name')->get();

        return response()->json($departments->toArray());
    }

    /**
     * Returns the roles of an event by 
     * id, 
     * name,
     * and id of the department they belong to 
     *
     * @param   Request $request    Incoming Request
     * @param   int     $id         Event ID
     * @return  void                JSON Response
     */
    public function roles(Request $request, $id)
    {
        $event = Event::find($id);
        $roles = $event->shifts()->select('id AS role_id', 'department_id', 'name')->get();

        return response()->json($roles->toArray());
    }

    /**
     * Returns the taken shifts of an event by: 
     * id,
     * start date,
     * end date,
     * start time, 
     * end time,
     * status of the user who took the shift,
     * id of the role they belong to,
     * id of the department the role belongs to,
     * and id of the user currently signed up for the shift,
     *  as well as the users full name and email
     *
     * @param   Request     $request    Incoming Request
     * @param   int         $id         Event ID
     * @return  Response                JSON Response
     */
    public function shifts(Request $request, $id)
    {
        $event = Event::find($id);

        // Query shifts that belong to the given event
        $query = DB::table('slots')
            ->leftJoin('schedule', 'slots.schedule_id', '=', 'schedule.id')
            ->leftJoin('departments', 'schedule.department_id', '=', 'departments.id')
            ->leftJoin('events', 'departments.event_id', '=', 'events.id')
            ->where('events.id', $event->id);
        // Only query shifts with users
        $query = $query->leftJoin('users', 'slots.user_id', '=', 'users.id')
            ->leftJoin('user_data', 'user_data.user_id', '=', 'users.id')
            ->whereNotNull('slots.user_id');
        
        $shifts = $query->select(
            'slots.id AS shift_id',
            'schedule.department_id',
            'schedule.shift_id AS role_id', // TEMP: Rename this after nomenclature change
            'schedule.start_date',
            'schedule.end_date',
            'schedule.start_time',
            'schedule.end_time',
            'slots.user_id',
            'users.email',
            'user_data.full_name',
            'slots.status'
        )->get();

        $shifts->each(function ($shift) {
            $shift->display_name = Helpers::displayName(User::find($shift->user_id));
        });

        return response()->json($shifts->toArray());
    }

    /**
     * Updates the shifts status/users-performance
     *
     * @param   Request   $request  Incoming Request
     * @param   int       $id       Shift ID
     * @return  Response            JSON Response
     */
    public function updateShift(Request $request, $id)
    {
        $slot = Slot::find($id);
        $slot->status = $request->get('status');
        $slot->save();

        return response('Success: Changed Status!', 200);
    }
}
