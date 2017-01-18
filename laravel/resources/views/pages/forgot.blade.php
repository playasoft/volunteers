@extends('app')

@section('content')
    <h1>Forgot your password?</h1>
    <hr>

    @if(isset($token))
        <p>
            Your email address has been confirmed. Please use this form to create a new password.
        </p>

        {!! Form::open() !!}
            @include('partials/form/password', ['name' => 'password', 'label' => 'Password', 'placeholder' => 'Your new password'])
            @include('partials/form/password', ['name' => 'password_confirmation', 'label' => 'Confirm Password', 'placeholder' => 'Type your password again'])

            <button type="submit" class="btn btn-primary">Submit</button>
        {!! Form::close() !!}
    @else
        {!! Form::open() !!}
            @include('partials/form/text', ['name' => 'user', 'label' => 'Username or Email', 'placeholder' => 'Enter your username or your email address'])

            <button type="submit" class="btn btn-primary">Submit</button>
        {!! Form::close() !!}
    @endif
@endsection
