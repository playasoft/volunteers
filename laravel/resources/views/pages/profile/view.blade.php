<?php

use App\Helpers;

?>

@extends('app')

@section('content')
    <h1>
        Your Profile

        <div class="pull-right" style="font-size:0.4em; margin-top: 1.4em;">
            User Permissions:

            <b>{{ implode(", ", Auth::user()->getRoleNames(['format' => 'ucwords'])) }}</b>
        </div>
    </h1>
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

        <br>
        <a href="/profile/edit" class="btn btn-primary">Edit Profile</a>
        <a href="/profile/password/edit" class="btn btn-primary">Change Password</a>
        <hr>

        <h3>Additional information</h3>

        <div class="row">
            <div class="col-sm-2 title">Full Name</div>
            <div class="col-sm-10 value">{{ $user->data->full_name or 'Not Provided' }}</div>
        </div>

        <div class="row">
            <div class="col-sm-2 title">Burner Name</div>
            <div class="col-sm-10 value">{{ Helpers::displayName($user, 'Not Provided') }}</div>
        </div>

        <div class="row">
            <div class="col-sm-2 title">Camp</div>
            <div class="col-sm-10 value">{{ $user->data->camp or 'Not Provided' }}</div>
        </div>

        <div class="row">
            <div class="col-sm-2 title">Phone</div>
            <div class="col-sm-10 value">{{ $user->data->phone or 'Not Provided' }}</div>
        </div>

        <div class="row">
            <div class="col-sm-2 title">Emergency Contact</div>
            <div class="col-sm-10 value">{{ $user->data->emergency_name or 'Not Provided' }}</div>
        </div>

        <div class="row">
            <div class="col-sm-2 title">Emergency Phone</div>
            <div class="col-sm-10 value">{{ $user->data->emergency_phone or 'Not Provided' }}</div>
        </div>

        <div class="row">
            <div class="col-sm-2 title">Birthday</div>
            <div class="col-sm-10 value">{{ $user->data->birthday or 'Not Provided' }}</div>
        </div>

        <br>
        <a href="/profile/data/edit" class="btn btn-primary">Edit Additional Information</a>
    </div>

    <hr>
    <p>
        Are you are a certified EMT or have CPR training? You can upload documents to your profile for administrators to verify.
        When approved, you will automatically be able to sign up for shifts that require training.
    </p>

    @if($user->uploads->count())
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

    <a href="/profile/upload" class="btn btn-primary">Upload a File</a>
@endsection
