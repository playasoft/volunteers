<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class DepartmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Display department creation page
    public function createForm(Request $request)
    {
        $this->authorize('create-department');
        return view('pages/department/create');
    }

    // Create a new department
    public function create(DepartmentRequest $request)
    {
        $this->authorize('create-department');
        return "Todo";
/*
        $input = $request->all();
        $department = Department::create($input);
        $department->save();

        $request->session()->flash('success', 'Your department has been created.');
        return redirect('/department/' . $department->id);
*/
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
        return "Todo";
        
/*
        $input = $request->all();
        $department->update($input);
        $department->save();

        $request->session()->flash('success', 'Department has been updated.');
        return redirect('/department/' . $department->id);
*/
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
        return "Todo";

/*
        $department->delete();

        $request->session()->flash('success', 'Department has been deleted.');
        return redirect('/');
*/
    }
}
