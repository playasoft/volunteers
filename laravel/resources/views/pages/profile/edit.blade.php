@extends('app')

@section('content')
    <h1>Edit Your Profile</h1>
    <hr>
    
    {!! Form::open() !!}
        <input type="hidden" name="type" value="account">

        @include('partials/form/text', ['name' => 'name', 'label' => 'Username', 'placeholder' => 'The name you log in with', 'value' => $user->name])
        @include('partials/form/text', ['name' => 'email', 'label' => 'Email address', 'placeholder' => 'Your email', 'value' => $user->email])
        @include('partials/form/password', ['name' => 'current_password', 'label' => 'Password', 'placeholder' => 'Your current password', 'help' => "In order to change your username or email, you must provide your password."])

        <button type="submit" class="btn btn-success">Save Changes</button>
        <a href="/profile" class="btn btn-primary">Cancel</a>
    {!! Form::close() !!}

    <hr>
    <h2>Change Your Password</h2>

    {!! Form::open() !!}
        <input type="hidden" name="type" value="password">

        @include('partials/form/password', ['name' => 'password', 'label' => 'Current Password', 'placeholder' => 'Are you who you say you are?'])
        @include('partials/form/password', ['name' => 'new_password', 'label' => 'New Password', 'placeholder' => 'An even better password'])
        @include('partials/form/password', ['name' => 'new_password_confirmation', 'label' => 'Confirm New Password', 'placeholder' => 'Type your password again'])

        <button type="submit" class="btn btn-success">Update Password</button>
        <a href="/profile" class="btn btn-primary">Cancel</a>
    {!! Form::close() !!}
    
    <hr>
    <h2>Additional Information</h2>

    {!! Form::open() !!}
        <input type="hidden" name="type" value="data">

        @include('partials/form/text', ['name' => 'burner_name', 'label' => 'Burner Name', 'placeholder' => 'Your name on the Playa', 'value' => (is_null($user->data)) ? '' : $user->data->burner_name])
        @include('partials/form/text', ['name' => 'real_name', 'label' => 'Real Name', 'placeholder' => 'Your name in the Default World', 'value' => (is_null($user->data)) ? '' : $user->data->real_name])
        @include('partials/form/date', ['name' => 'birthday', 'label' => 'Birthday', 'placeholder' => 'When were you born?', 'value' => (is_null($user->data)) ? '' : $user->data->birthday])

        <button type="submit" class="btn btn-success">Save Changes</button>
        <a href="/profile" class="btn btn-primary">Cancel</a>
    {!! Form::close() !!}
@endsection
