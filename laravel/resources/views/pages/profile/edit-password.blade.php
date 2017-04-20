@extends('app')

@section('content')
    <h1>Change Your Password</h1>

    {!! Form::open(['url' => 'profile/edit']) !!}
        <input type="hidden" name="type" value="password">

        @include('partials/form/password', ['name' => 'password', 'label' => 'Current Password', 'placeholder' => 'Are you who you say you are?'])
        @include('partials/form/password', ['name' => 'new_password', 'label' => 'New Password', 'placeholder' => 'An even better password'])
        @include('partials/form/password', ['name' => 'new_password_confirmation', 'label' => 'Confirm New Password', 'placeholder' => 'Type your password again'])

        <button type="submit" class="btn btn-success">Update Password</button>
        <a href="/profile" class="btn btn-primary">Cancel</a>
    {!! Form::close() !!}
@endsection
