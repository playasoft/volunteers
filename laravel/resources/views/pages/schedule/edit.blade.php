@extends('app')

@section('content')

    <div class="header-buttons pull-right">
        @can('delete-schedule')
            <a href="/schedule/{{ $schedule->id }}/delete" class="btn btn-danger">Delete Shift</a>
        @endcan
    </div>

    <h1>Editing Shift for: {{ $schedule->department->name }}</h1>
    <hr>

    {!! Form::open() !!}
    
        <div class="form-group {{ ($errors->has('department_id')) ? 'has-error' : '' }}">
            <label class="control-label" for="department-field">Department</label>
    
            <select name="department_id" class="form-control" id="department-field">
                <option value="">Select a department</option>
                
                @foreach($schedule->event->departments as $department)
                    <option value="{{ $department->id }}" {{ $schedule->department->id == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                @endforeach
            </select>

            @if($errors->has('department_id'))
                <span class="help-block">{{ $errors->first('department_id') }}</span>
            @endif
        </div>
        
        @include('partials/form/text', ['name' => 'name', 'label' => 'Shift Name', 'placeholder' => "Name for this schedule", 'value' => $schedule->data->name])
        @include('partials/form/date', ['name' => 'start_date', 'label' => 'Start Date', 'value' => $schedule->start_date])
        @include('partials/form/date', ['name' => 'end_date', 'label' => 'End Date', 'value' => $schedule->end_date])
        @include('partials/form/time', ['name' => 'start_time', 'label' => 'Start Time', 'help' => "The time of day when the first schedule starts", 'value' => $schedule->start_time])
        @include('partials/form/time', ['name' => 'end_time', 'label' => 'End Time', 'help' => "The time of day when the last schedule ends", 'value' => $schedule->end_time])
        @include('partials/form/time', ['name' => 'duration', 'label' => 'Duration', 'help' => "The duration of each slot in this schedule", 'value' => $schedule->duration])
        @include('partials/roles', ['roles' => json_decode($schedule->getRoles()), 'help' => "By default, roles will be inherited from the department. You can use these options to override the default."])

        <button type="submit" class="btn btn-success">Save Changes</button>
        <a href="/event/{{ $schedule->event->id }}" class="btn btn-primary">Cancel</a>
        
    {!! Form::close() !!}
@endsection
