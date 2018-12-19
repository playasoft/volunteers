<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\DepartmentRequest;
use App\Models\Department;
use App\Models\Event;

use App\Events\EventChanged;

class DepartmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('bindings');
    }

    // Display list of departments in an event
    public function listDepartments(Request $request, Event $event)
    {
        $this->authorize('read-department');
        return view('pages/department/list', compact('event'));
    }

    // Display department creation page
    public function createForm(Request $request, Event $event)
    {
        $this->authorize('create-department');
        return view('pages/department/create', compact('event'));
    }

    // Create a new department
    public function create(DepartmentRequest $request)
    {
        $this->authorize('create-department');
        $input = $request->all();

        $department = new Department;
        $department->event_id = $input['event_id'];
        $department->save();
        $department->update($input);
        
        event(new EventChanged($department->event, ['type' => 'department', 'status' => 'created']));

        $request->session()->flash('success', 'Your department has been created.');
        return redirect('/event/' . $department->event->id . '/departments');
    }

    // View form to edit an existing department
    public function editForm(Request $request, Department $department)
    {
        $this->authorize('edit-department');
        return view('pages/department/edit', compact('department'));
    }

    // Save changes to an existing department
    public function edit(DepartmentRequest $request, Department $department)
    {
        $this->authorize('edit-department');
        $input = $request->all();

        $department->update($input);

        event(new EventChanged($department->event, ['type' => 'department', 'status' => 'edited']));

        $request->session()->flash('success', 'Department has been updated.');
        return redirect('/event/' . $department->event->id . '/departments');
    }

    // View confirmation page before deleting an department
    public function deleteForm(Request $request, Department $department)
    {
        $this->authorize('delete-department');
        return view('pages/department/delete', compact('department'));
    }

    // Delete an department
    public function delete(Request $request, Department $department)
    {
        $this->authorize('delete-department');
        $event = $department->event;
        $department->delete();

        event(new EventChanged($event, ['type' => 'department', 'status' => 'deleted']));

        $request->session()->flash('success', 'Department has been deleted.');
        return redirect('/event/' . $department->event->id . '/departments');
    }
}
