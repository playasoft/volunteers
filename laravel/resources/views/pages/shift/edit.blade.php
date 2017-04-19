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
                
                @foreach($shift->event->departments->sortBy('name') as $department)
                    <option value="{{ $department->id }}" {{ $shift->department->id == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                @endforeach
            </select>

            @if($errors->has('department_id'))
                <span class="help-block">{{ $errors->first('department_id') }}</span>
            @endif
        </div>
        
        @include('partials/form/text', ['name' => 'name', 'label' => 'Shift Name', 'placeholder' => "Name for this shift", 'value' => $shift->name])
        @include('partials/form/textarea', ['name' => 'description', 'label' => 'Description', 'placeholder' => "The best shift you'll ever sign up for!", 'value' => $shift->description])
        @include('partials/roles', ['selected' => $shift->roles, 'help' => "These are the defaults for this shift. You can still customize training options later when adding a shift to the schedule."])

        <button type="submit" class="btn btn-success">Save Changes</button>
        <a href="/event/{{ $shift->event->id }}" class="btn btn-primary">Cancel</a>
        
    {!! Form::close() !!}
@endsection
