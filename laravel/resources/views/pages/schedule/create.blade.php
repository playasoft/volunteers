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

// Generate array of event days to be displayed as checkboxes
$days = [];

foreach($event->days() as $day)
{
    $days[$day->date->format('Y-m-d')] = $day->name . " (" . $day->date->format('Y-m-d') . ")";
}

?>

@extends('app')

@section('content')
    <h1>Add a shift to the schedule for: {{ $event->name }}</h1>
    <hr>

    {{-- Output available shifts as JSON so the shift dropdown can be dynamically populated --}}
    <textarea class="hidden available-shifts">{{ json_encode($shifts) }}</textarea>

    {!! Form::open(['url' => '/schedule', 'class' => 'edit-schedule']) !!}
        <div class="col-md-6">
            @if($event->departments->count())
                <div class="form-group {{ ($errors->has('department_id')) ? 'has-error' : '' }}">
                    <label class="control-label" for="department-field">Department</label>

                    <select name="department_id" class="form-control department-dropdown" id="department-field">
                        <option value="">Select a department</option>

                        @foreach($event->departments->sortBy('name') as $department)
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

            <div class="form-group {{ ($errors->has('shift_id')) ? 'has-error' : '' }}">
                <label class="control-label" for="shift-field">Shift</label>

                <select name="shift_id" class="form-control shift-dropdown" id="shift-field">
                    <option value="">Select a shift</option>
                    <option value="" class="dynamic">This list will be automatically updated after selecting a department</option>
                </select>

                @if($errors->has('shift_id'))
                    <span class="help-block">{{ $errors->first('shift_id') }}</span>
                @endif
            </div>

            @include('partials/roles', ['help' => "Prevents volunteers from signing up for this shift unless they have recieved specific training."])
            @include('partials/form/text', ['name' => 'password', 'label' => 'Password', 'help' => "Volunteers won't be able to sign up without this password. Leave blank for none."])
            @include('partials/form/text', ['name' => 'volunteers', 'label' => 'Number of volunteers needed', 'help' => "This determines how many slots are available for the shift."])
        </div>

        <div class="col-md-6">
            <div class="alert alert-danger shift-warning hidden">
                <b>Oops!</b> No shifts have been created for this department yet.
                @can('create-shift')
                    <a href="/event/{{ $event->id }}/shift/create">Click here</a> to create a shift.
                @endcan
            </div>

            @include('partials/form/checkbox', ['name' => 'dates', 'label' => 'Event Dates', 'options' => $days])

            <div class="custom-wrap">
                @include('partials/form/select',
                [
                    'name' => 'start_time',
                    'label' => 'Start Time',
                    'help' => "The time of day when the first shift starts",
                    'options' =>
                    [
                        '' => 'Select a time',
                        '0:00' => 'Midnight (beginning of day)',
                        '6:00' => '6 AM',
                        '9:00' => '9 AM',
                        '12:00' => 'Noon',
                        'custom' => 'Other'
                    ]
                ])

                <div class="custom hidden">
                    @include('partials/form/time', ['name' => 'custom_start_time', 'label' => 'Custom Start Time'])
                </div>
            </div>

            <div class="custom-wrap">
                @include('partials/form/select',
                [
                    'name' => 'duration',
                    'label' => 'Shift Length',
                    'help' => "The duration of each slot in this shift",
                    'options' =>
                    [
                        '' => 'Select a duration',
                        '3:00' => 'Regular Shift (3 hours)',
                        '6:00' => 'Shift Lead (6 hours)',
                        'custom' => 'Other'
                    ]
                ])

                <div class="custom hidden">
                    @include('partials/form/text', ['name' => 'custom_duration', 'label' => 'Custom Duration', 'placeholder' => 'hh:mm'])
                </div>
            </div>

            <div class="checkbox">
                <label>
                    <input type="checkbox" name="does_slot_repeat" value="true">
                    Does this shift repeat?
                </label>
            </div>

            <div class="slot-repeat custom-wrap hidden">
                @include('partials/form/text', ['name' => 'slot_repeat', 'label' => 'How many times?'])
                <span class="help-block">Shifts will end at <span class="slot-end"></span></span>
            </div>

            <input type="hidden", name="end_time"/>
            <input type="hidden", name="custom_end_time"/>
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
        <a href="/event/{{ $event->id }}" class="btn btn-danger">Cancel</a>

        <br><br>
        <div class='preview'></div>
    {!! Form::close() !!}
@endsection
