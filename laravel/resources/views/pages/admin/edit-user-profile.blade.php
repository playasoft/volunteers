<?php

use App\Helpers;

?>

@extends('app')

@section('content')
    <h1>Edit User Information</h1>
    <p>Warning: you are about to change another user's information</P>
    <hr>

    <div class="profile">

        {!! Form::open(['url' => 'user/'.$user->id.'/edit']) !!}
        <input type="hidden" name="type" value="data">
        <input type="hidden" class="csrf-token" value="{{ csrf_token() }}">
        
        @include('partials/form/text',
        [
            'name' => 'full_name',
            'label' => 'Full Name',
            'placeholder' => 'Your name in the Default World',
            'help' => "Required. Your full name is used for reporting and ticketing purposes",
            'value' => (is_null($user->data)) ? '' : $user->data->full_name
        ])

        @include('partials/form/text',
        [
            'name' => 'burner_name',
            'label' => 'Burner Name',
            'placeholder' => 'Your name on the Playa',
            'help' => "This name will be shown to other users when you sign up for a shift",
            'value' => Helpers::displayName($user)
        ])

        @include('partials/form/text',
        [
            'name' => 'camp',
            'label' => 'Your Camp',
            'placeholder' => 'Camp Creative Name',
            'help' => "Enter your camp name if you have one, or 'open camping' if not",
            'value' => (is_null($user->data)) ? '' : $user->data->camp
        ])

        @include('partials/form/text',
        [
            'name' => 'phone',
            'label' => 'Phone Number',
            'value' => (is_null($user->data)) ? '' : $user->data->phone
        ])

        @include('partials/form/text',
        [
            'name' => 'emergency_name',
            'label' => 'Emergency Contact',
            'value' => (is_null($user->data)) ? '' : $user->data->emergency_name
        ])

        @include('partials/form/text',
        [
            'name' => 'emergency_phone',
            'label' => 'Emergency Phone Number',
            'value' => (is_null($user->data)) ? '' : $user->data->emergency_phone
        ])

        @include('partials/form/date',
        [
            'name' => 'birthday',
            'label' => 'Birthday',
            'placeholder' => 'YYYY-MM-DD',
            'help' => 'This will only be used as a part of the event census',
            'value' => (is_null($user->data)) ? '' : $user->data->birthday
        ])

        <button type="submit" class="btn btn-success">Save Changes</button>
        <a href='/user/{{$user->id }}' class="btn btn-primary">Cancel</a>
        {!! Form::close() !!}
        
        <h3>User Roles</h3>

        <div class="user-roles">
            <input type="hidden" class="user-id" value="{{ $user->id }}">
            <input type="hidden" class="csrf-token" value="{{ csrf_token() }}">

            @include('partials/form/checkbox', ['name' => 'roles', 'options' => $roleNames, 'selected' => $user->getRoleNames()])
        </div>

        <div class="buttons">
            <a class="save-roles btn btn-success">Save</a>
            <a class="cancel-roles btn btn-danger">Cancel</a>
        </div>

        
    </div>

    @if($user->uploads->count())
        <hr>
        <h2>Uploaded Files</h2>

        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>File</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>
                @foreach($user->uploads as $upload)
                    <tr>
                        <td>{{ $upload->name }}</td>
                        <td>{{ $upload->description }}</td>
                        <td><a href='/files/user/{{ $upload->file }}'>{{ $upload->file }}</a></td>
                        <td>{{ ucwords($upload->status) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection
