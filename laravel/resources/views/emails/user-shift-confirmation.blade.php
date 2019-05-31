<h1>You're signed up for the {{ $slot->schedule->shift->name }} shift!</h1>

@if ($adminAssigned)
<p>
   This is a confirmation email for the {{ $slot->schedule->shift->name }} shift
   that you were recently assigned to for the {{ $slot->schedule->shift->event->name }} event.
</p>
@else
<p>
   This is a confirmation email for the {{ $slot->schedule->shift->name }} shift
   you recently picked up for the {{ $slot->schedule->shift->event->name }} event.
</p>
@endif

<p>
    This shift takes place on {{ $slot->start_date }} between the times of
    {{ $slot->start_time }} and {{ $slot->end_time }}.
</p>

<p>
    If you did <b>NOT</b> sign-up for this shift or would like to <b>CANCEL</b> this
    shift, <a href="{{ env('SITE_URL').'/slot'.$slot->id.'/view' }}">click here</a>.
</p>

<p>
Otherwise, we look forward to seeing you there!
</p>
