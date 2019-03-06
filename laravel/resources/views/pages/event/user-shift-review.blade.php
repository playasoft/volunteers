@extends('app')

@section('content')

<h1 class="title">User Shift Review</h1>
@if($event->departments->count())
            <h2>Available Shifts</h2>

            <form class="form-inline event-filter">
                Filter:

                <div class="form-group">
                    <select class="form-control filter-days">
                        <option value="all">Show All Days</option>

                        @foreach($event->days(true) as $day)
                            <option value="{{ $day->date->format('Y-m-d') }}">{{ $day->name }} - {{ $day->date->format('Y-m-d') }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <select class="form-control filter-departments">
                        <option value="all">Show All Departments</option>
                        @foreach($event->departments->sortBy('name') as $department)
                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                        @endforeach
                    </select>
                </div>
            </form>

            <hr>
        <div class="days">
            @foreach($event->days(true) as $day)
                    <div class="day" data-date="{{ $day->date->format('Y-m-d') }}">
                        <div class="heading">
                            <h3>{{ $day->name }}</h3>
                            &mdash; <i>{{ $day->date->format('Y-m-d') }}</i>
                        </div>

                        <div class="shift-wrap">
        

                            <div class="department-wrap">
                                @foreach($event->departments->sortBy('name') as $department)
                                    <?php

                                    if($department->slots->where('start_date', $day->date->format('Y-m-d'))->isEmpty())
                                        continue;
                                    ?>

                                    <div class="department" data-id="{{ $department->id }}">
                                        <div class="title">
                                            <b>{{ $department->name }}</b>

                                            @if($department->description)
                                                <span class="description">
                                                    <span class="glyphicon glyphicon-question-sign"></span>

                                                    <div class="tip hidden">
                                                        {!! nl2br(e($department->description)) !!}
                                                        <hr>
                                                        <a class="btn btn-primary">Close</a>
                                                    </div>
                                                </span>
                                            @endif

                                            @can('edit-department')
                                                <span class="edit">
                                                    <a href="/department/{{ $department->id }}/edit">
                                                        <span class="glyphicon glyphicon-pencil"></span>
                                                    </a>
                                                </span>
                                            @endcan
                                        </div>

                                        <ul class="shifts">
                                            @foreach($department->schedule as $schedule)
                                               

                                                
                                            @endforeach
                                        </ul>
                                    </div>
                                @endforeach
                            </div> <!-- / .department-wrap -->
                        </div> <!-- / .shift-wrap -->
                    </div>
                @endforeach
            </div>
@endif

@endsection