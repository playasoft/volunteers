<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\ShiftRequest;
use App\Models\Shift;
use App\Models\Department;
use App\Models\Event;

class ShiftController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
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

        // Convert roles into JSON
        $input['roles'] = json_encode($input['roles']);

        // Check if the current roles match the department roles
        if(json_encode($input['roles']) == $department->roles)
        {
            // Unset the roles, use department as default instead
            unset($input['roles']);
        }

        $shift = Shift::create($input);

        $request->session()->flash('success', 'Your shift has been created.');
        return redirect('/event/' . $department->event->id);
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
        return 'todo';

/*
        $input = $request->all();

        // Convert roles into JSON
        $input['roles'] = json_encode($input['roles']);

        $shift->update($input);

        $request->session()->flash('success', 'Shift has been updated.');
        return redirect('/event/' . $shift->event->id);
*/
    }

    // View confirmation page before deleting an shift
    public function deleteForm(Request $request, Shift $shift)
    {
        $this->authorize('delete-shift');
        return view('pages/shift/delete', compact('shift'));
    }

    // Delete an shift
    public function delete(Request $request, Shift $shift)
    {
        $this->authorize('delete-shift');
        return 'todo';

/*
        $event = $shift->event;
        $shift->delete();

        $request->session()->flash('success', 'Shift has been deleted.');
        return redirect('/event/' . $event->id);
*/
    }
}
