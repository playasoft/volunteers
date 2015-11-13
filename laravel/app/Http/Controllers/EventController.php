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
        //
    }
}
