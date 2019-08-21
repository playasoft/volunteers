<?php

use App\Helpers;

$url = "/slot/{$slot->id}/take";

$taken = false;
$self = false;
$other = false;

if(!empty($slot->user))
{
    $taken = true;
    if(Auth::check() && Auth::user()->id == $slot->user->id)
    {
        $self = true;
        $url = "/slot/{$slot->id}/release";
    }
    else
    {
        $other = true;
    }

    if(Auth::check() && (Auth::user()->hasRole('admin') || Auth::user()->hasRole('department-lead')))
    {
        $adminUrl = "/slot/{$slot->id}/adminRelease";
    }
}
else
{
    if(Auth::check() && Auth::user()->hasRole('admin') || Auth::user()->hasRole('department-lead'))
    {
        $adminUrl = "/slot/{$slot->id}/adminAssign";
    }
}

?>

@extends('app')

@section('content')

    <h1>
        @if($taken)
            @if($self)
                Your Volunteer Shift for:
            @else
                Occupied Volunteer Shift for:
            @endif
        @else
            Available for Volunteering:
        @endif

        {{ $slot->department->name }} - {{ $slot->schedule->shift->name }}
    </h1>

    <hr>

    {!! Form::open(['url' => $url]) !!}
        <div>
            <label>Start Date</label>
            {{ $slot->start_date }}
        </div>

        <div>
            <label>Start Time</label>
            {{ $slot->start_time }}
        </div>

        <div>
            <label>End Time</label>
            {{ $slot->end_time }}
        </div>

        @if($slot->department->description)
            <label>About {{ $slot->department->name }}</label>
            <p>{!! nl2br(e($slot->department->description)) !!}</p>
        @endif

        @if($slot->schedule->shift->description)
            <label>About {{ $slot->schedule->shift->name }}</label>
            <p>{!! nl2br(e($slot->schedule->shift->description)) !!}</p>
        @endif

        @if($taken)
            <hr>

            @if($self)
                <p>
                    Are you sure you want to cancel volunteering for this shift?
                    By canceling, your slot will be available for other people to take.
                </p>

                <button type="submit" class="btn btn-danger">Release Shift</button>
            @else
                <p>
                    This slot has been taken by <b>{{ Helpers::displayName($slot->user) }}</b>.
                </p>

                @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('department-lead'))
                    <div class="profile">
                        <div class="row">
                            <div class="col-sm-2 title">Email</div>
                            <div class="col-sm-10 value">{{ $slot->user->email }}</div>
                        </div>

                        <div class="row">
                            <div class="col-sm-2 title">Full Name</div>
                            <div class="col-sm-10 value">{{ $slot->user->data->full_name or 'Not Provided' }}</div>
                        </div>

                        <div class="row">
                            <div class="col-sm-2 title">Burner Name</div>
                            <div class="col-sm-10 value">{{ Helpers::displayName($slot->user, 'Not Provided') }}</div>
                        </div>

                        <div class="row">
                            <div class="col-sm-2 title">Performance</div>
                            <div class="col-sm-10 value volunteer">
                                <input type="hidden" class="csrf-token" value="{{ csrf_token() }}">
                                <input type="hidden" class="slot-number" value="{{ $slot->id }}">

                                <select class="volunteer-status" data-status="{{ $slot->status }}">
                                    <option value="">Select One</option>
                                    <option value="flaked">Flaked</option>
                                    <option value="late">Late</option>
                                    <option value="ontime">On Time</option>
                                    <option value="excellent">Excellent</option>
                                </select>

                                <span class="buttons">&ensp;
                                    <a class="save-status">Save</a>&ensp;
                                    <a class="cancel-status">Cancel</a>
                                </span>
                            </div>
                        </div>
                    </div>
                @endif
            @endif
        @else
            {{-- If nobody has taken this slot, display more information and the take button --}}

            @if($slot->schedule->password)
                <div>
                    @include('partials/form/text', ['name' => 'password', 'label' => 'This shift requires a password', 'help' => "This shift has been reserved. You must recieve a password from the department lead in order to take this shift."])
                </div>
            @else
                <div>
                    <label>Allowed User Groups</label>
                    <ul>
                        @foreach($slot->schedule->getRoles() as $scheduleRole)
                            <li>{{ ucwords($scheduleRole->role->name) }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <hr>

            <p>
                Are you sure you want to volunteer for this shift?
                By registering, you will be expected to perform the duties of this shift for the entire time listed.
                Please arrive at least 15 minutes ahead of time to be briefed by the previous shift team and answer any questions you have.
            </p>

            <button type="submit" class="btn btn-success">Take Shift</button>
        @endif

        <a href="/event/{{ $slot->event->id }}" class="btn btn-primary">Back to Event</a>

        @if((Auth::user()->hasRole('admin') || Auth::user()->hasRole('department-lead')) && $taken && $other)
        <p>
            Are you sure you want to remove this user for this shift?
            By releasing {{$slot->user->data->burner_name}}, their slot will be available for other people to take.
        </p>
        <button formaction="{{ $adminUrl }}" type="submit" class="btn btn-danger">Release Shift</button>
        @endif
    {!! Form::close() !!}

    <?php
        $event = $slot->event;
    ?>
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

        <?php
            $displaced_event_slots = App\Models\Slot::onlyTrashed()
                ->leftJoin('schedule', 'slots.schedule_id', '=', 'schedule.id')
                ->leftJoin('departments', 'schedule.department_id', '=', 'departments.id')
                ->leftJoin('events', 'departments.event_id', '=', 'events.id')
                ->where('event_id', $event->id)
                ->get();
        ?>
        @if($displaced_event_slots->isNotEmpty())
        <div class="shift row alert alert-danger">
            <h1>!!! Alert: Users need reassignment !!!</h1>
            <div class="slots col-sm-3">
                @foreach($displaced_event_slots as $slot)
                    <span class="">
                        <a href="/slot/{{ $slot->id }}/view" class="slot taken" title="{{ App\Helpers::displayName($slot->user) }}">
                            {{ App\Helpers::displayName($slot->user) }}
                        </a>
                    </span>                
                @endforeach
            </div>
        </div>
        @endif

        <div class="days">
            @foreach($event->days(true) as $day)
                <div class="day" data-date="{{ $day->date->format('Y-m-d') }}">
                    <div class="heading">
                        <h3>{{ $day->name }}</h3>
                        &mdash; <i>{{ $day->date->format('Y-m-d') }}</i>
                    </div>

                    <div class="shift-wrap">
                        @include('partials/event/timegrid')

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
                                            <?php

                                            if($schedule->slots->where('start_date', $day->date->format('Y-m-d'))->isEmpty())
                                                continue;

                                            ?>

                                            <li class="shift row" data-rows="{{ $schedule->volunteers }}">
                                                <div class="title col-sm-2">
                                                    <b>{{ $schedule->shift->name }}</b>

                                                    @if($schedule->shift->description)
                                                        <span class="description">
                                                            <span class="glyphicon glyphicon-question-sign"></span>

                                                            <div class="tip hidden">
                                                                {!! nl2br(e($schedule->shift->description)) !!}

                                                                <hr>
                                                                <a class="btn btn-primary">Close</a>
                                                            </div>
                                                        </span>
                                                    @endif

                                                    @can('edit-schedule')
                                                        <span class="edit">
                                                            <a href="/schedule/{{ $schedule->id }}/edit">
                                                                <span class="glyphicon glyphicon-pencil"></span>
                                                            </a>
                                                        </span>
                                                    @endcan
                                                </div>

                                                <div class="slots col-sm-10">
                                                    @foreach($schedule->slots->where('start_date', $day->date->format('Y-m-d')) as $slot)
                                                        @include('partials/event/slot')
                                                    @endforeach
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endforeach
                        </div> <!-- / .department-wrap -->
                    </div> <!-- / .shift-wrap -->
                </div>
            @endforeach
        </div>

        <hr>

        @can('create-department')
            <a href="/event/{{ $event->id }}/department/create" class="btn btn-primary">Create Department</a>
        @endcan

        @can('create-shift')
            <a href="/event/{{ $event->id }}/shift/create" class="btn btn-primary">Create Shift</a>
        @endcan
    @endif
@endsection
