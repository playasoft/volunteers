<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\EventRequest;
use App\Models\Event;

use App\Events\EventChanged;

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
            $fileName = preg_replace('/[^a-z0-9-_]/', '', $file['filename']) . "." . preg_replace('/[^a-z0-9-_]/', '', $file['extension']);

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
        $event->delete();

        event(new EventChanged($event, ['type' => 'event', 'status' => 'deleted']));

        $request->session()->flash('success', 'Event has been deleted.');
        return redirect('/');
    }

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

        // Get current event information
        // Loop through event dates
        // Create a map of old vs new dates
        // Create new event with updated dates

        // Loop through event departments
        // Create new departments
        // Create a map of old vs new department IDs

        // Loop through shifts
        // Adjust shift dates based on date map
        // Create new shifts based on new department IDs

        $request->session()->flash('success', 'Event has been cloned.');
        return redirect('/');
    }
}
