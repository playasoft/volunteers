@extends('app')

@section('content')

    <div class="header-buttons pull-right">
        @can('delete-shift')
            <a href="/shift/{{ $shift->id }}/delete" class="btn btn-danger">Delete Shift</a>
        @endcan
    </div>

    <h1>Editing Shift for: {{ $shift->department->name }}</h1>
    <hr>

    {!! Form::open() !!}
    
        <div class="form-group {{ ($errors->has('department_id')) ? 'has-error' : '' }}">
            <label class="control-label" for="department-field">Department</label>
    
            <select name="department_id" class="form-control" id="department-field">
                <option value="">Select a department</option>
                
                @foreach($shift->event->departments as $department)
                    <option value="{{ $department->id }}" {{ $shift->department->id == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                @endforeach
            </select>

            @if($errors->has('department_id'))
                <span class="help-block">{{ $errors->first('department_id') }}</span>
            @endif
        </div>
        
        @include('partials/form/text', ['name' => 'name', 'label' => 'Shift Name', 'placeholder' => "Name for this shift", 'value' => $shift->name])

        <div class="form-group">
            <label class="control-label" for="date-field">Shift Type</label>
    
            <select class="form-control" id="date-field">
                <option value="all">Recurring, every day</option>
                <option value="some">Recurring, date range</option>
                <option value="one">Single day</option>
            </select>
        </div>

        <div class="start-date hidden">
            @include('partials/form/date', ['name' => 'start_date', 'label' => 'Start Date'])
        </div>

        <div class="end-date hidden">
            @include('partials/form/date', ['name' => 'end_date', 'label' => 'End Date'])
        </div>

        @include('partials/form/time', ['name' => 'start_time', 'label' => 'Start Time', 'help' => "The time of day when the first shift starts", 'value' => $shift->start])
        @include('partials/form/time', ['name' => 'end_time', 'label' => 'End Time', 'help' => "The time of day when the last shift ends", 'value' => $shift->end])
        @include('partials/form/time', ['name' => 'duration', 'label' => 'Duration', 'help' => "The duration of each slot in this shift", 'value' => $shift->duration])
        @include('partials/roles', ['roles' => json_decode($shift->getRoles()), 'help' => "By default, roles will be inherited from the department. You can use these options to override the default."])

        <button type="submit" class="btn btn-success">Save Changes</button>
        <a href="/event/{{ $shift->event->id }}" class="btn btn-primary">Cancel</a>
        
    {!! Form::close() !!}
@endsection
