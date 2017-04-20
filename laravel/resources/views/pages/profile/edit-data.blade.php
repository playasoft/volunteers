@extends('app')

@section('content')
    <h1>Additional Profile Information</h1>
    <hr>

    {!! Form::open(['url' => 'profile/edit']) !!}
        <input type="hidden" name="type" value="data">

        @include('partials/form/text', ['name' => 'full_name', 'label' => 'Full Name', 'placeholder' => 'Your name in the Default World', 'help' => "Your full name is required for reporting and ticketing purposes", 'value' => (is_null($user->data)) ? '' : $user->data->full_name])
        @include('partials/form/text', ['name' => 'burner_name', 'label' => 'Burner Name', 'placeholder' => 'Your name on the Playa', 'help' => "Optional. This name will appear when you sign up for a shift", 'value' => (is_null($user->data)) ? '' : $user->data->burner_name])
        @include('partials/form/date', ['name' => 'birthday', 'label' => 'Birthday', 'placeholder' => 'When were you born?', 'value' => (is_null($user->data)) ? '' : $user->data->birthday])

        <button type="submit" class="btn btn-success">Save Changes</button>
        <a href="/profile" class="btn btn-primary">Cancel</a>
    {!! Form::close() !!}
@endsection
