<?php

// Generate list of available shifts by department
$shifts = [];

foreach($event->departments as $department)
{
    $shifts[$department->id] = [];

    foreach($department->shifts as $shift)
    {
        $shifts[$department->id][] =
        [
            'id' => $shift->id,
            'name' => $shift->name
        ];
    }
}

?>

@extends('app')

@section('content')
    <h1>Add a shift to the schedule for: {{ $event->name }}</h1>
    <hr>

    {{-- Output available shifts as JSON so the shift dropdown can be dynamically populated --}}
    <textarea class="hidden available-shifts">{{ json_encode($shifts) }}</textarea>
    
    {!! Form::open(['url' => '/shift']) !!}
        @if($event->departments->count())
            <div class="form-group {{ ($errors->has('department_id')) ? 'has-error' : '' }}">
                <label class="control-label" for="department-field">Department</label>

                <select name="department_id" class="form-control department-dropdown" id="department-field">
                    <option value="">Select a department</option>

                    @foreach($event->departments as $department)
                        <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                    @endforeach
                </select>

                @if($errors->has('department_id'))
                    <span class="help-block">{{ $errors->first('department_id') }}</span>
                @endif
            </div>
        @else
            <div class="alert alert-danger">
                <b>Oops!</b> A department has to be created before you can make a shift.

                @can('create-department')
                    <a href="/event/{{ $event->id }}/department/create">Click here</a> to create your first department.
                @endcan
            </div>
        @endif

        <div class="form-group {{ ($errors->has('shift_data_id')) ? 'has-error' : '' }}">
            <label class="control-label" for="shift-field">Shift</label>

            <select name="shift_data_id" class="form-control shift-dropdown" id="shift-field">
                <option value="">Select a shift</option>
                <option value="" class="dynamic">This list will be automatically updated after selecting a department</option>
            </select>

            @if($errors->has('shift_data_id'))
                <span class="help-block">{{ $errors->first('shift_data_id') }}</span>
            @endif
        </div>

        <div class="alert alert-danger shift-warning hidden">
            <b>Oops!</b> No shifts have been created for this department yet.

            @can('create-shift')
                <a href="/event/{{ $event->id }}/shift/create">Click here</a> to create a shift.
            @endcan
        </div>

        <div class="form-group">
            <label class="control-label" for="date-field">Shift Type</label>
    
            <select class="form-control shift-type" id="date-field">
                <option value="all">Recurring, every day</option>
                <option value="some">Recurring, date range</option>
                <option value="one">Single day</option>
            </select>
        </div>

        <div class="shift start-date hidden">
            @include('partials/form/date', ['name' => 'start_date', 'label' => 'Start Date'])
        </div>

        <div class="shift end-date hidden">
            @include('partials/form/date', ['name' => 'end_date', 'label' => 'End Date'])
        </div>
        
        @include('partials/form/time', ['name' => 'start_time', 'label' => 'Start Time', 'help' => "The time of day when the first shift starts"])
        @include('partials/form/time', ['name' => 'end_time', 'label' => 'End Time', 'help' => "The time of day when the last shift ends"])
        @include('partials/form/time', ['name' => 'duration', 'label' => 'Duration', 'help' => "The duration of each slot in this shift"])
        @include('partials/roles', ['help' => "By default, roles will be inherited from the department. You can use these options to override the default."])

        <button type="submit" class="btn btn-primary">Submit</button>
        <a href="/event/{{ $event->id }}" class="btn btn-danger">Cancel</a>
    {!! Form::close() !!}
@endsection
