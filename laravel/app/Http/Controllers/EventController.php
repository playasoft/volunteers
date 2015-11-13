<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\EventRequest;
use App\Models\Event;

class EventController extends Controller
{
    // Display event creation page
    public function get(Request $request)
    {
        $this->authorize('create-event');
        return view('pages/event');
    }

    // Create a new event
    public function create(EventRequest $request)
    {
        $input = $request->all();
        $event = Event::create($input);

        // Save event image with a unique name
        if($request->hasFile('image'))
        {
            // Create upload folder if it doesn't exist
            if(!file_exists(public_path() . '/img/upload'))
            {
                mkdir(public_path() . '/img/upload', 0755, true);
            }

            // Make sure the original filename is sanitized
            $file = pathinfo($request->file('image')->getClientOriginalName());
            $fileName = preg_replace('/[^a-z0-9-_]/', '', $file['filename']) . "." . preg_replace('/[^a-z0-9-_]/', '', $file['extension']);

            // Move file to uploads directory
            $event->image = time() . '-' . $fileName;
            $request->file('image')->move(public_path() . '/img/upload', $event->image);
        }

        $event->save();

        $request->session()->flash('success', 'Your event has been created.');
        return redirect('/event/' . $event->id);
    }
}
