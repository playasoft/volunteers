@extends('app')

@section('content')
    <h1>
        Your Profile
        <div class="pull-right" style="font-size:0.4em; margin-top: 1.4em;">User Level: <b>{{ ucfirst($user->role) }}</b></div>
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
    </div>

    <a href="/profile/edit" class="btn btn-primary">Edit Profile</a>
    <a href="/profile/upload" class="btn btn-primary">Upload a File</a>

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
