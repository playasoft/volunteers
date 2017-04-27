@extends('app')

@section('content')
    <h1>Register an Account</h1>
    <hr>

    {!! Form::open() !!}
        @include('partials/form/text', ['name' => 'name', 'label' => 'Username', 'placeholder' => 'Your login name'])
        @include('partials/form/text', ['name' => 'email', 'label' => 'Email address', 'placeholder' => 'Your email'])
        @include('partials/form/password', ['name' => 'password', 'label' => 'Password', 'placeholder' => 'A strong password'])
        @include('partials/form/password', ['name' => 'password_confirmation', 'label' => 'Confirm Password', 'placeholder' => 'Type your password again'])

        <button type="submit" class="btn btn-primary">Submit</button>
    {!! Form::close() !!}

    <hr>
    <p>
        Already registered? <a href="/login/">Login Here!</a><br>
        Forgot your password? <a href="/forgot/">Reset it!</a>
    <p>
@endsection
