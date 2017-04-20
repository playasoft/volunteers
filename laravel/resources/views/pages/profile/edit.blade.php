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
@endsection
