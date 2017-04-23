@extends('app')

@section('content')
    <h1>Login to Your Account</h1>
    <hr>

    {!! Form::open() !!}
        @include('partials/form/text', ['name' => 'name', 'label' => 'Username or Email', 'placeholder' => 'You can use your username or your email address'])
        @include('partials/form/password', ['name' => 'password', 'label' => 'Password', 'placeholder' => 'Your password'])

        <p>
            <a href="/forgot">Forgot password?</a>
        </p>
 
        <button type="submit" class="btn btn-primary">Submit</button>
    {!! Form::close() !!}
@endsection
