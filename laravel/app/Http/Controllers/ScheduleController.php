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

    // Display schedule creation page
    public function createForm(Request $request, Event $event)
    {
        $this->authorize('create-schedule');
        return view('pages/schedule/create', compact('event'));
    }

    // Create a new schedule
    public function create(ShiftRequest $request)
    {
        $this->authorize('create-schedule');
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
        $schedule = Shift::create($input);

        // Generate slots based on schedule options
        Slot::generate($schedule);
        event(new EventChanged($department->event, ['type' => 'schedule', 'status' => 'created']));

        $request->session()->flash('success', 'Your shift has been added to the schedule.');
        return redirect('/event/' . $department->event->id);
    }

    // View form to edit an existing schedule
    public function editForm(Request $request, Shift $schedule)
    {
        $this->authorize('edit-schedule');

        // Format the schedule start, end, and duration times
        $schedule->formatTimes();

        return view('pages/schedule/edit', compact('schedule'));
    }

    // Save changes to an existing schedule
    public function edit(ShiftRequest $request, Shift $schedule)
    {
        $this->authorize('edit-schedule');
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
        $schedule->formatTimes();

        // Check if the start time, end time, or duration are changing
        $regenerateSlots = false;
        
        if($schedule->start_date != $input['start_date'] ||
            $schedule->end_date != $input['end_date'] ||
            $schedule->start_time != $input['start_time'] ||
            $schedule->end_time != $input['end_time'] ||
            $schedule->duration != $input['duration'])
        {
            $regenerateSlots = true;
        }

        $schedule->update($input);

        // Regenerate slots after the updated schedule information is saved
        if($regenerateSlots)
        {
            Slot::generate($schedule);
        }

        event(new EventChanged($schedule->event, ['type' => 'schedule', 'status' => 'edited']));
        
        $request->session()->flash('success', 'Shift schedule has been updated.');
        return redirect('/event/' . $schedule->event->id);
    }

    // View confirmation page before deleting a schedule
    public function deleteForm(Request $request, Shift $schedule)
    {
        $this->authorize('delete-schedule');
        return view('pages/schedule/delete', compact('schedule'));
    }

    // Delete a schedule
    public function delete(Request $request, Shift $schedule)
    {
        $this->authorize('delete-schedule');
        $event = $schedule->department->event;
        $schedule->delete();

        event(new EventChanged($event, ['type' => 'schedule', 'status' => 'deleted']));

        $request->session()->flash('success', 'Shift has been deleted from the schedule.');
        return redirect('/event/' . $event->id);
    }
}
