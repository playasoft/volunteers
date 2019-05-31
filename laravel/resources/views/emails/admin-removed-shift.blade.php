<h1>Hi {{ $user_name }}!</h1>

<p>
    It seems you've been effected by a recent scheduling change for the {{ $shift_name }} shift.
    If you'd like reshedule, click here:
      <a href="{{ env('SITE_URL') . '/event/' . $slot->schedule->department->event->id }}">
        {{ env('SITE_NAME') }}
      </a>.
</p>
