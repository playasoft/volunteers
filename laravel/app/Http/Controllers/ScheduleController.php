<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\ScheduleRequest;
use App\Models\Event;
use App\Models\Department;
use App\Models\Shift;
use App\Models\Schedule;
use App\Models\Slot;
use App\Models\EventRole;

use App\Events\EventChanged;

class ScheduleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Helper function to convert form input into database-friendly information
    private function parseInput($request)
    {
        $input = $request->all();
        $department = Department::find($input['department_id']);
        $shift = Shift::find($input['shift_id']);

        // Conditional validation rules for custom inputs
        if($request->input('start_time') == 'custom')
        {
            $this->validate($request, ['custom_start_time' => 'required|time']);
            $input['start_time'] = $input['custom_start_time'];
        }

        if($request->input('end_time') == 'custom')
        {
            $this->validate($request, ['custom_end_time' => 'required|time']);
            $input['end_time'] = $input['custom_end_time'];
        }

        if($request->input('duration') == 'custom')
        {
            $this->validate($request, ['custom_duration' => 'required|date_format:h:i']);
            $input['duration'] = $input['custom_duration'];
        }

        // Determine the schedule start and end dates
        // TODO: Actually parse the dates and make sure they're sorted properly
        $input['start_date'] = array_slice($input['dates'], 0, 1)[0];
        $input['end_date'] = array_slice($input['dates'], -1, 1)[0];
        $input['dates'] = json_encode($input['dates']);

        if(isset($input['roles']))
        {
            // Check if the current roles match the shift roles
            if($input['roles'] == $shift->getRoleNames())
            {
                // Unset the roles, use shift as default instead
                unset($input['roles']);
            }
        }

        // Make sure dates and times are properly formatted
        $input = Schedule::setDates($department, $input);
        $input = Schedule::setTimes($input);

        return $input;
    }

    // Display schedule creation page
    public function createForm(Request $request, Event $event)
    {
        $this->authorize('create-schedule');
        return view('pages/schedule/create', compact('event'));
    }

    // Create a new schedule
    public function create(ScheduleRequest $request)
    {
        $this->authorize('create-schedule');
        $input = $this->parseInput($request);
        $department = Department::find($input['department_id']);
        $shift = Shift::find($input['shift_id']);
        $schedule = Schedule::create($input);

        // Sync roles if provided
        if(isset($input['roles']))
        {
            EventRole::syncForeign($department->event, 'App\Models\Schedule', $schedule->id, $input['roles']);
        }

        // Generate slots based on schedule options
        Slot::generate($schedule);
        event(new EventChanged($department->event, ['type' => 'schedule', 'status' => 'created']));

        $request->session()->flash('success', 'Your shift has been added to the schedule.');
        return redirect('/event/' . $department->event->id);
    }

    // View form to edit an existing schedule
    public function editForm(Request $request, Schedule $schedule)
    {
        $this->authorize('edit-schedule');

        // Format the schedule start, end, and duration times
        $schedule->formatTimes();

        return view('pages/schedule/edit', compact('schedule'));
    }

    // Save changes to an existing schedule
    public function edit(ScheduleRequest $request, Schedule $schedule)
    {
        $this->authorize('edit-schedule');
        $input = $this->parseInput($request);

        // Make sure dates and times are set properly and formatted
        $schedule->formatTimes();

        // Check if the start time, end time, or duration are changing
        $regenerateSlots = false;
        $volunteersChanged = false;
        
        if($schedule->start_date != $input['start_date'] ||
            $schedule->end_date != $input['end_date'] ||
            $schedule->start_time != $input['start_time'] ||
            $schedule->end_time != $input['end_time'] ||
            $schedule->duration != $input['duration'] ||
            $schedule->dates != $input['dates'])
        {
            $regenerateSlots = true;
        }

        // If we're not already regenerating slots, but the number of volunteers is different
        if(!$regenerateSlots && $schedule->volunteers != $input['volunteers'])
        {
            $volunteersChanged = true;
            $originalVolunteers = $schedule->volunteers;
        }

        $schedule->update($input);

        // Sync roles if provided
        if(isset($input['roles']))
        {
            EventRole::syncForeign($schedule->department->event, 'App\Models\Schedule', $schedule->id, $input['roles']);
        }

        // Regenerate slots after the updated schedule information is saved
        if($regenerateSlots)
        {
            Slot::generate($schedule);
        }

        if($volunteersChanged)
        {
            Slot::volunteersChanged($schedule, $originalVolunteers);
        }

        event(new EventChanged($schedule->event, ['type' => 'schedule', 'status' => 'edited']));
        
        $request->session()->flash('success', 'Schedule schedule has been updated.');
        return redirect('/event/' . $schedule->event->id);
    }

    // View confirmation page before deleting a schedule
    public function deleteForm(Request $request, Schedule $schedule)
    {
        $this->authorize('delete-schedule');
        return view('pages/schedule/delete', compact('schedule'));
    }

    // Delete a schedule
    public function delete(Request $request, Schedule $schedule)
    {
        $this->authorize('delete-schedule');
        $event = $schedule->department->event;
        $schedule->delete();

        event(new EventChanged($event, ['type' => 'schedule', 'status' => 'deleted']));

        $request->session()->flash('success', 'Schedule has been deleted from the schedule.');
        return redirect('/event/' . $event->id);
    }
}
