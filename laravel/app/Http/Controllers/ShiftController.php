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
use App\Models\EventRole;

use App\Events\EventChanged;

class ShiftController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('bindings');
    }

    // Display list of shifts in an event
    public function listShifts(Request $request, Event $event)
    {
        $this->authorize('read-shift');
        return view('pages/shift/list', compact('event'));
    }

    // Display shift creation page
    public function createForm(Request $request, Event $event)
    {
        $this->authorize('create-shift');
        return view('pages/shift/create', compact('event'));
    }

    // Create a new shift
    public function create(ShiftRequest $request)
    {
        $this->authorize('create-shift');
        $input = $request->all();
        $department = Department::find($input['department_id']);

        $input['event_id'] = $department->event->id;
        $shift = Shift::create($input);
        EventRole::syncForeign($department->event, 'App\Models\Shift', $shift->id, $input['roles']);

        $request->session()->flash('success', 'Your shift has been created.');
        return redirect('/event/' . $department->event->id . '/shifts');
    }

    // View form to edit an existing shift
    public function editForm(Request $request, Shift $shift)
    {
        $this->authorize('edit-shift');
        return view('pages/shift/edit', compact('shift'));
    }

    // Save changes to an existing shift
    public function edit(ShiftRequest $request, Shift $shift)
    {
        $this->authorize('edit-shift');
        $input = $request->all();
        $department = Department::find($input['department_id']);

        $shift->update($input);
        EventRole::syncForeign($department->event, 'App\Models\Shift', $shift->id, $input['roles']);

        $request->session()->flash('success', 'Shift has been updated.');
        return redirect('/event/' . $shift->event->id . '/shifts');
    }

    // View confirmation page before deleting a shift
    public function deleteForm(Request $request, Shift $shift)
    {
        $this->authorize('delete-shift');
        return view('pages/shift/delete', compact('shift'));
    }

    // Delete a shift
    public function delete(Request $request, Shift $shift)
    {
        $this->authorize('delete-shift');
        $event = $shift->event;
        $shift->delete();

        $request->session()->flash('success', 'Shift has been deleted.');
        return redirect('/event/' . $event->id . '/shifts');
    }
}
