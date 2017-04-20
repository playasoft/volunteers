@extends('app')

@section('content')
    <h1>Additional Profile Information</h1>
    <hr>

    {!! Form::open(['url' => 'profile/edit']) !!}
        <input type="hidden" name="type" value="data">

        @include('partials/form/text', ['name' => 'burner_name', 'label' => 'Burner Name', 'placeholder' => 'Your name on the Playa', 'value' => (is_null($user->data)) ? '' : $user->data->burner_name])
        @include('partials/form/text', ['name' => 'real_name', 'label' => 'Real Name', 'placeholder' => 'Your name in the Default World', 'value' => (is_null($user->data)) ? '' : $user->data->real_name])
        @include('partials/form/date', ['name' => 'birthday', 'label' => 'Birthday', 'placeholder' => 'When were you born?', 'value' => (is_null($user->data)) ? '' : $user->data->birthday])

        <button type="submit" class="btn btn-success">Save Changes</button>
        <a href="/profile" class="btn btn-primary">Cancel</a>
    {!! Form::close() !!}
@endsection
