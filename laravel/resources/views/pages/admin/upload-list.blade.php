<?php

$statuses =
[
    'pending' => "Pending",
    'approved-medical' => "Approved - Medical",
    'approved-fire' => "Approved - Fire",
    'approved-ranger' => "Approved - Ranger",
    'denied' => "Denied"
];

?>

@extends('app')

@section('content')
    <h1>Uploaded Files</h1>
    <hr>

    <input type="hidden" class="csrf-token" value="{{ csrf_token() }}">

    <table class="table table-hover">
        <thead>
            <tr>
                <th>User</th>
                <th>Name</th>
                <th>Description</th>
                <th>File</th>
                <th>Status</th>
            </tr>
        </thead>

        <tbody>
            @foreach($uploads as $upload)
                <tr class="upload" data-id="{{ $upload->id }}">
                    <td><a href="/user/{{ $upload->user->id }}">{{ $upload->user->name }}</a></td>
                    <td>{{ $upload->name }}</td>
                    <td>{{ $upload->description }}</td>
                    <td><a href='/files/user/{{ $upload->file }}'>{{ $upload->file }}</a></td>
                    <td>
                        <select class="upload-status" data-status="{{ $upload->status }}">
                            @foreach($statuses as $status => $title)
                                @if($status == $upload->status)
                                    <option value="{{ $status }}" selected>{{ $title }}</option>
                                @else
                                    <option value="{{ $status }}">{{ $title }}</option>
                                @endif
                            @endforeach
                        </select>

                        <span class="buttons">
                            &ensp;

                            <a class="save-upload">
                                Save
                            </a>

                            &ensp;

                            <a class="cancel-upload">
                                Cancel
                            </a>
                        </span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
{{ $uploads->links() }}
@endsection
