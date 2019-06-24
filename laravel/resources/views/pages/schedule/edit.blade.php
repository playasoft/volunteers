<?php

// Generate list of available shifts by department
$shifts = [];

foreach($schedule->event->departments as $department)
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

foreach($schedule->event->days() as $day)
{
    $days[$day->date->format('Y-m-d')] = $day->name . " (" . $day->date->format('Y-m-d') . ")";
}

?>

@extends('app')

@section('content')
    <h1>Editing schedule for: {{ $schedule->department->name }}</h1>
    <hr>

    {{-- Output available shifts as JSON so the shift dropdown can be dynamically populated --}}
    <textarea class="hidden available-shifts">{{ json_encode($shifts) }}</textarea>

    {!! Form::open(array('class' => 'edit-schedule')) !!}
        <div class="col-md-6">
            @if($schedule->event->departments->count())
                <div class="form-group {{ ($errors->has('department_id')) ? 'has-error' : '' }}">
                    <label class="control-label" for="department-field">Department</label>

                    <select name="department_id" class="form-control department-dropdown" id="department-field">
                        <option value="">Select a department</option>

                        @foreach($schedule->event->departments->sortBy('name') as $department)
                            <option value="{{ $department->id }}" {{ $schedule->department->id == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
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
                        <a href="/event/{{ $schedule->event->id }}/department/create">Click here</a> to create your first department.
                    @endcan
                </div>
            @endif

            <div class="form-group {{ ($errors->has('shift_id')) ? 'has-error' : '' }}">
                <label class="control-label" for="shift-field">Shift</label>

                <select name="shift_id" class="form-control shift-dropdown" id="shift-field" data-saved="{{ $schedule->shift_id }}">
                    <option value="">Select a shift</option>
                    <option value="" class="dynamic">This list will be automatically updated after selecting a department</option>
                </select>

                @if($errors->has('shift_id'))
                    <span class="help-block">{{ $errors->first('shift_id') }}</span>
                @endif
            </div>

            @include('partials/roles', ['selected' => $schedule->getRoles(), 'help' => "Prevents volunteers from signing up for this shift unless they have recieved specific training."])
            @include('partials/form/text', ['name' => 'password', 'label' => 'Password', 'help' => "Volunteers won't be able to sign up without this password. Leave blank for none.", 'value' => $schedule->password])
            @include('partials/form/text', ['name' => 'volunteers', 'label' => 'Number of volunteers needed', 'help' => "This determines how many slots are available for each shift.", 'value' => $schedule->volunteers])
        </div>

        <div class="col-md-6">
            @include('partials/form/checkbox', ['name' => 'dates', 'label' => 'Days', 'help' => 'The event days which have this shift.', 'options' => $days, 'selected' => json_decode($schedule->dates)])

            <div class="custom-wrap">
                @include('partials/form/select',
                [
                    'name' => 'start_time',
                    'label' => 'Start Time',
                    'help' => "The time of day when the first shift starts",
                    'options' =>
                    [
                        '' => 'Select a time',
                        '00:00' => 'Midnight (beginning of day)',
                        '06:00' => '6 AM',
                        '09:00' => '9 AM',
                        '12:00' => 'Noon',
                        'custom' => 'Other'
                    ],
                    'value' => $schedule->start_time
                ])

                <div class="custom hidden">
                    @include('partials/form/time', ['name' => 'custom_start_time', 'label' => 'Custom Start Time', 'value' => $schedule->start_time])
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
                        '03:00' => 'Regular Shift (3 hours)',
                        '06:00' => 'Shift Lead (6 hours)',
                        'custom' => 'Other'
                    ],
                    'value' => $schedule->duration
                ])

                <div class="custom hidden">
                    @include('partials/form/text', ['name' => 'custom_duration', 'label' => 'Custom Duration', 'placeholder' => 'hh:mm', 'value' => $schedule->duration])
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

            <input type="hidden", name="end_time" value="{{ $schedule->end_time }}"/>
            <input type="hidden", name="custom_end_time" value="{{ $schedule->end_time }}"/>
        </div>

        <button type="submit" class="btn btn-success">Save Changes</button>

        <a href="/event/{{ $schedule->event->id }}" class="btn btn-primary">Cancel</a>

        @can('delete-schedule')
            <a href="/schedule/{{ $schedule->id }}/delete" class="btn btn-danger">Delete from the Schedule</a>
        @endcan

        <br><br>
        <div class='preview'></div>
    {!! Form::close() !!}
@endsection
