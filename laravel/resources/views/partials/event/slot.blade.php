<?php

$class = "slot empty";
$href = "/slot/{$slot->id}/view";
$name = "";

// If there is no slot user, display a link to the take shift page
if(is_null($slot->user))
{
    $start = strtotime($slot->start_time);
    $end = strtotime($slot->end_time);

    $name = date("h:i a", $start) . " - " . date("h:i a", $end);
}

// If there is a slot user, set the class to taken
else
{
    $class = "slot taken";
    $name = $slot->user->name;

    // If the slot is taken by the current user, display a link to the release page
    if($slot->user->id === Auth::user()->id)
    {
        $class = "slot taken-by-current-user";
    }

    // If the user has profile data saved, and has a burner name
    if(!is_null($slot->user->data) && !is_null($slot->user->data->burner_name))
    {
        $name = $slot->user->data->burner_name;
    }
}

if($href)
{
    $href = "href='{$href}'";
}

// If the event has passed, remove any links
$start_date = new \Carbon\Carbon($slot->start_date);

if($start_date->lt(\Carbon\Carbon::now()))
{
    $href = "";
}

?>

<span class="slot-wrap" data-start="{{ $slot->start_time }}" data-duration="{{ $schedule->duration }}" data-row="{{ $slot->row }}">
    <a {!! $href !!} class="{{ $class }}" data-id="{{ $slot->id }}" title="{{ $name }}">{{ $name }}</a>
</span>
