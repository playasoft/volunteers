<?php

$roles = ['admin', 'volunteer', 'veteran', 'medical', 'fire'];

?>

@extends('app')

@section('content')
    <h1>Viewing User Profile</h1>
    <hr>

    <div class="profile">
        <div class="row">
            <div class="col-sm-2 title">Username</div>
            <div class="col-sm-10 value">{{ $user->name }}</div>
        </div>

        <div class="row">
            <div class="col-sm-2 title">Email</div>
            <div class="col-sm-10 value">{{ $user->email }}</div>
        </div>

        <h3>Additional information</h3>

        <div class="row">
            <div class="col-sm-2 title">Burner Name</div>
            <div class="col-sm-10 value">{{ $user->data->burner_name or 'Not Provided' }}</div>
        </div>

        <div class="row">
            <div class="col-sm-2 title">Real Name</div>
            <div class="col-sm-10 value">{{ $user->data->real_name or 'Not Provided' }}</div>
        </div>

        <div class="row">
            <div class="col-sm-2 title">Birthday</div>
            <div class="col-sm-10 value">{{ $user->data->birthday or 'Not Provided' }}</div>
        </div>

        <h3>User Role</h3>

        <input type="hidden" class="user-id" value="{{ $user->id }}">
        <input type="hidden" class="csrf-token" value="{{ csrf_token() }}">

        <select class="user-role form-control" data-role="{{ $user->role }}">
            @foreach($roles as $role)
                @if($role == $user->role)
                    <option value="{{ $role }}" selected>{{ ucwords($role) }}</option>
                @else
                    <option value="{{ $role }}">{{ ucwords($role) }}</option>
                @endif
            @endforeach
        </select>

        <div class="buttons">
            <a class="save-role btn btn-success">Save</a>
            <a class="cancel-role btn btn-danger">Cancel</a>
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
