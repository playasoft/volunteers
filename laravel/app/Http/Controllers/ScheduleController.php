<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\ShiftRequest;
use App\Models\Event;
use App\Models\Department;
use App\Models\Shift;
use App\Models\Slot;

use App\Events\EventChanged;

class ScheduleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Display list of shifts in an event
    public function listShifts(Request $request, Event $event)
    {
        $this->authorize('create-shift');
        return view('pages/schedule/list', compact('event'));
    }

    // Display shift creation page
    public function createForm(Request $request, Event $event)
    {
        $this->authorize('create-shift');
        return view('pages/schedule/create', compact('event'));
    }

    // Create a new shift
    public function create(ShiftRequest $request)
    {
        $this->authorize('create-shift');
        $input = $request->all();
        $department = Department::find($input['department_id']);

        if(isset($input['roles']))
        {
            // Convert roles into JSON
            $input['roles'] = json_encode($input['roles']);

            // Check if the current roles match the department roles
            if($input['roles'] == $department->roles)
            {
                // Unset the roles, use department as default instead
                unset($input['roles']);
            }
        }
        
        // Set start and end dates if not included 
        $input = Shift::setDates($department, $input);
        $input = Shift::setTimes($input);
        $shift = Shift::create($input);

        // Generate slots based on shift options
        Slot::generate($shift);
        event(new EventChanged($department->event, ['type' => 'shift', 'status' => 'created']));

        $request->session()->flash('success', 'Your shift has been created.');
        return redirect('/event/' . $department->event->id);
    }

    // View form to edit an existing shift
    public function editForm(Request $request, Shift $shift)
    {
        $this->authorize('edit-shift');

        // Format the shift start, end, and duration times
        $shift->formatTimes();

        return view('pages/schedule/edit', compact('shift'));
    }

    // Save changes to an existing shift
    public function edit(ShiftRequest $request, Shift $shift)
    {
        $this->authorize('edit-shift');
        $input = $request->all();
        $department = Department::find($input['department_id']);

        // Convert roles into JSON
        $input['roles'] = json_encode($input['roles']);

        // Check if the current roles match the department roles
        if($input['roles'] == $department->roles)
        {
            // Unset the roles, use department as default instead
            unset($input['roles']);
        }

        // Make sure dates and times are set properly and formatted
        $input = Shift::setDates($department, $input);
        $input = Shift::setTimes($input);
        $shift->formatTimes();

        // Check if the start time, end time, or duration are changing
        $regenerateSlots = false;
        
        if($shift->start_date != $input['start_date'] ||
            $shift->end_date != $input['end_date'] ||
            $shift->start_time != $input['start_time'] ||
            $shift->end_time != $input['end_time'] ||
            $shift->duration != $input['duration'])
        {
            $regenerateSlots = true;
        }

        $shift->update($input);

        // Regenerate slots after the updated shift information is saved
        if($regenerateSlots)
        {
            Slot::generate($shift);
        }

        event(new EventChanged($shift->event, ['type' => 'shift', 'status' => 'edited']));
        
        $request->session()->flash('success', 'Shift has been updated.');
        return redirect('/event/' . $shift->event->id);
    }

    // View confirmation page before deleting a shift
    public function deleteForm(Request $request, Shift $shift)
    {
        $this->authorize('delete-shift');
        return view('pages/schedule/delete', compact('shift'));
    }

    // Delete a shift
    public function delete(Request $request, Shift $shift)
    {
        $this->authorize('delete-shift');
        $event = $shift->department->event;
        $shift->delete();

        event(new EventChanged($event, ['type' => 'shift', 'status' => 'deleted']));

        $request->session()->flash('success', 'Shift has been deleted.');
        return redirect('/event/' . $event->id);
    }
}
