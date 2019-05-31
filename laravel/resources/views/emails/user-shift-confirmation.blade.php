<h1>You're signed up for the {{ $shift_name }} shift!</h1>

@if ($admin_assigned)
<p>
   This is a confirmation email for the {{ $shift_name }} shift
   that you were recently assigned to for the {{ $event_name }} event.
</p>
@else
<p>
   This is a confirmation email for the {{ $shift_name }} shift
   you recently picked up for the {{ $shift_name }} event.
</p>
@endif

<p>
    This shift takes place on {{ $start_date}} between the times of
    {{ $start_time }} and {{ $end_time }}.
</p>

<p>
    If you did <b>NOT</b> sign-up for this shift or would like to <b>CANCEL</b> this
    shift, <a href="{{ env('SITE_URL').'/slot/'.$slot->id.'/view' }}">click here</a>.
</p>

<p>
    Otherwise, we look forward to seeing you there!
</p>
