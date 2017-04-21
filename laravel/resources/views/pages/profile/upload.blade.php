@extends('app')

@section('content')
    <h1>Upload a File</h1>
    <hr>

    <p>
        Some shifts like EMT or fire fighting may only allow certain user groups to sign up.
        In order to sign up for these shifts, you are required to upload documentation certifying that you are qualified.
    </p>

    <p>
        After uploading a file, it will be reviwed by administrator and you will be notified when it is approved.
    </p>

    {!! Form::open(['files' => true]) !!}
        @include('partials/form/text', ['name' => 'name', 'label' => 'File Name', 'placeholder' => "What is this file?"])
        @include('partials/form/text', ['name' => 'description', 'label' => 'Description', 'placeholder' => "A short description (optional)"])
        @include('partials/form/file', ['name' => 'file', 'label' => 'Upload'])

        <button type="submit" class="btn btn-success">Upload File</button>
        <a href="/profile" class="btn btn-primary">Cancel</a>
    {!! Form::close() !!}
@endsection
