<h1>You're signed up for a {{ $shift_name }} shift at {{ $event_name }}!</h1>
<p>
    Your shift will take place on {{ $start_date}} between {{ $start_time }} and {{ $end_time }}.
</p>
<p>
    For volunteer ticketing information, please visit the {{ $event_name }} ticketing webpage. 
</p>
<p>
    If you did NOT sign up for this shift or would like to CANCEL this shift, <a href="{{ env('SITE_URL').'/slot/'.$slot->id.'/view' }}">click here</a>.
</p>
<p>
    Otherwise, we look forward to seeing you there!
</p>
