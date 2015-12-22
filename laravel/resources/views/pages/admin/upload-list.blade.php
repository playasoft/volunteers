<?php

$statuses = ['pending', 'approved', 'denied'];

?>

@extends('app')

@section('content')
    <h1>Uploaded Files</h1>
    <hr>

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
                        <select class="status">
                            @foreach($statuses as $status)
                                @if($status == $upload->status)
                                    <option selected>{{ ucwords($status) }}</option>
                                @else
                                    <option>{{ ucwords($status) }}</option>
                                @endif
                            @endforeach
                        </select>

                        <a class="save">
                            Save
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

@endsection
