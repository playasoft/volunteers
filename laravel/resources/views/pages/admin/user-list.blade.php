
<?php 
    use \App\Helpers;
    $userRoles = \App\Models\Role::get();
?>

@extends('app')

@section('content')
    <h1>Registered Users </h1>
    <form class="user-list" method="GET" action="/users">
        <div class="col-sm-8 input-group">
            <input type="text" name="search" class=" form-control" placeholder="Search by email or username" value="{{ $_GET['search'] or '' }}">

        <div class="input-group-btn">
            <button type="submit" class=" btn btn-primary"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
        </div>
        </div>
        
        <div class="form-group col-sm-3">
            <select name="role" class="form-control filter-user" >
                <option value="">Filter Permissions</option>
                @foreach($userRoles as $role)
                    @if(!empty($_GET['role']) and $_GET['role'] == $role->id)
                        <option selected="true" value="{{ $role->id }}">{{$role->name}}</option>
                    @else
                        <option value="{{ $role->id }}">{{$role->name}}</option>
                    @endif
                @endforeach
            </select>
        </div>
    </form>
    <hr>

    <table class="table table-hover">
        <thead>
            <tr>
                <th>Username</th>
                <th>Full Name</th>
                <th>Burner Name</th>
                <th>Email</th>
                <th>Roles</th>
                <th>Date Registered</th>
            </tr>
        </thead>

        <tbody>
            @foreach($users as $user)
                <tr>
                    <td><a href="/user/{{ $user->id }}">{{ $user->name }}</a></td>
                    <td>{{ $user->data->full_name or '' }}</td>
                    <td>{{ Helpers::displayName($user) }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ implode(", ", $user->getRoleNames()) }}</a></td>
                    <td>{{ $user->created_at->format('Y-m-d') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@if($users instanceof \Illuminate\Pagination\LengthAwarePaginator)
{{ $users->appends(request()->input())->links() }}
@endif()
@endsection
