<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\EventRequest;
use App\Models\Event;
use App\Models\Department;
use App\Models\Schedule;
use App\Models\Slot;

use App\Events\EventChanged;
use Carbon\Carbon;

class EventController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    // Private function to manage file uploads
    private function handleUpload($request)
    {
        $fileName = false;
        
        // Save event image with a unique name
        if($request->hasFile('image'))
        {
            // Create upload folder if it doesn't exist
            if(!file_exists(public_path() . '/files/event'))
            {
                mkdir(public_path() . '/files/event', 0755, true);
            }

            // Make sure the original filename is sanitized
            $file = pathinfo($request->file('image')->getClientOriginalName());
            $fileName = preg_replace('/[^a-z0-9-_]/i', '', $file['filename']) . "." . preg_replace('/[^a-z0-9-_]/i', '', $file['extension']);

            // Move file to uploads directory
            $fileName = time() . '-' . $fileName;
            $request->file('image')->move(public_path() . '/files/event', $fileName);
        }

        return $fileName;
    }

    // Display event creation page
    public function createForm(Request $request)
    {
        $this->authorize('create-event');
        return view('pages/event/create');
    }

    // Create a new event
    public function create(EventRequest $request)
    {
        $this->authorize('create-event');

        $input = $request->all();
        $event = Event::create($input);

        // Save event image if a file was uploaded
        if($request->hasFile('image'))
        {
            $event->image = $this->handleUpload($request);
        }
        
        $event->save();

        $request->session()->flash('success', 'Your event has been created.');
        return redirect('/event/' . $event->id);
    }

    // View an existing event
    public function view(Request $request, Event $event)
    {
        return view('pages/event/view', compact('event'));
    }

    // View form to edit an existing event
    public function editForm(Request $request, Event $event)
    {
        $this->authorize('edit-event');
        return view('pages/event/edit', compact('event'));
    }

    // Save edits to an existing event
    public function edit(EventRequest $request, Event $event)
    {
        $this->authorize('edit-event');
        $input = $request->all();
        $event->update($input);

        if($request->hasFile('image'))
        {
            $event->image = $this->handleUpload($request);
        }

        $event->save();
        event(new EventChanged($event, ['type' => 'event', 'status' => 'edited']));

        $request->session()->flash('success', 'Event has been updated.');
        return redirect('/event/' . $event->id);
    }

    // View confirmation page before deleting an event
    public function deleteForm(Request $request, Event $event)
    {
        $this->authorize('delete-event');
        return view('pages/event/delete', compact('event'));
    }

    // Delete an event
    public function delete(Request $request, Event $event)
    {
      
        $this->authorize('delete-event');

        event(new EventChanged($event, ['type' => 'event', 'status' => 'deleted']));
        
        $event->delete();

        $request->session()->flash('success', 'Event has been deleted.');
        return redirect('/');
    }

/*

The clone feature has been disabled until the upcoming schema changes are finalized.

TODO: Fix this.


    // View confirmation page before cloning an event
    public function cloneForm(Request $request, Event $event)
    {
        $this->authorize('create-event');
        return view('pages/event/clone', compact('event'));
    }

    // Clone an event
    public function cloneEvent(Request $request, Event $event)
    {
        $this->authorize('create-event');
        $this->validate($request,
        [
            'start_date' => 'required|date_format:Y-m-d',
        ]);

        // Set up event information
        $startDate = new Carbon($event->start_date);
        $endDate = new Carbon($event->end_date);
        $newStartDate = new Carbon($request->input('start_date'));
        $departments = $event->departments;

        // Find the difference of the start dates
        $difference = $startDate->diffInSeconds($newStartDate);

        // Create new event from old event data
        $newEvent = Event::create([
            'name' => $event->name,
            'description' => $event->description,
            'start_date' => $startDate->addSeconds($difference)->format('Y-m-d'),
            'end_date' => $endDate->addSeconds($difference)->format('Y-m-d'),
        ]);

        // Add the image manually because it's not automatically fillable
        if($event->image)
        {
            $newEvent->image = $event->image;
            $newEvent->save();
        }

        // Loop through event departments
        foreach($departments as $department)
        {
            // Create new department
            $newDepartment = new Department;
            $newDepartment->event_id = $newEvent->id;
            $newDepartment->save();

            // Because the event_id isn't fillable, we have to define it first and then update
            $newDepartment->update([
                'name' => $department->name,
                'description' => $department->description,
                'roles' => $department->roles,
            ]);

            // Loop through shifts
            $shifts = $department->shifts;

            foreach($shifts as $shift)
            {
                // Adjust shift dates
                $shift->start_date = new Carbon($shift->start_date);
                $shift->end_date = new Carbon($shift->end_date);

                $shift->start_date = $shift->start_date->addSeconds($difference)->format('Y-m-d');
                $shift->end_date = $shift->end_date->addSeconds($difference)->format('Y-m-d');

                // Update the department ID
                $shift->department_id = $newDepartment->id;

                $newShift = Shift::create([
                    'department_id' => $newDepartment->id,
                    'shift_data_id' => $shift->data->id,
                    'start_date' => $shift->start_date,
                    'end_date' => $shift->end_date,
                    'start_time' => $shift->start_time,
                    'end_time' => $shift->end_time,
                    'duration' => $shift->duration,
                    'roles' => $shift->roles,
                ]);

                Slot::generate($newShift);
            }
        }

        $request->session()->flash('success', 'Event has been cloned.');
        return redirect('/event/' . $newEvent->id);
    }
*/
}
