@extends('app')

@section('content')
    <h1>Create a Shift for: {{ $event->name }}</h1>
    <hr>
    
    {!! Form::open(['url' => '/shift']) !!}
        @if($event->departments->count())
            <div class="form-group {{ ($errors->has('department_id')) ? 'has-error' : '' }}">
                <label class="control-label" for="department-field">Department</label>
        
                <select name="department_id" class="form-control" id="department-field">
                    <option value="">Select a department</option>
                    
                    @foreach($event->departments as $department)
                        <option value="{{ $department->id }}">{{ $department->name }}</option>
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
                    <a href="/event/{{ $event->id }}/department">Click here</a> to create your first department.
                @endcan
            </div>
        @endif
        
        @include('partials/form/text', ['name' => 'name', 'label' => 'Shift Name', 'placeholder' => "Name for this shift"])
        @include('partials/form/time', ['name' => 'start', 'label' => 'Start Time', 'help' => "The time of day when the first shift starts"])
        @include('partials/form/time', ['name' => 'end', 'label' => 'End Time', 'help' => "The time of day when the last shift ends"])
        @include('partials/form/time', ['name' => 'duration', 'label' => 'Duration', 'help' => "The duration of each slot in this shift"])
        @include('partials/roles', ['roles' => json_decode($department->roles), 'help' => "By default, roles will be inherited from the department. You can use these options to override the default."])

        <button type="submit" class="btn btn-primary">Submit</button>
    {!! Form::close() !!}
@endsection
