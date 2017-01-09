<?php

$statuses = ['pending', 'approved', 'denied'];

?>

@extends('app')

@section('content')
    <h1>Generate Reports</h1>
    <hr>

    <ul>
        <li>Reports by User</li>
        <li>Reports by Department</li>
        <li>Reports by Day</li>
    </ul>
@endsection
