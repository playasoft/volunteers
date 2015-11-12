<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class EventController extends Controller
{
    // Display event creation page
    public function get(Request $request)
    {
        $this->authorize('create-event');
        return view('pages/event');
    }
}
