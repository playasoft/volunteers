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
        
        @include('partials/form/text', ['name' => 'name', 'label' => 'Shift Name', 'placeholder' => "Name for this shift"])
        @include('partials/form/textarea', ['name' => 'description', 'label' => 'Description', 'placeholder' => "The best shift you'll ever sign up for!"])
        @include('partials/roles', ['help' => "These are the defaults for this shift. You can still customize training options later when adding a shift to the schedule."])

        <button type="submit" class="btn btn-primary">Submit</button>
    {!! Form::close() !!}
@endsection
