<h1>Hi {{ $user_name }}!</h1>

<p>
    There was a scheduling change and you were removed from the {{ $shift_name }} shift on {{ $shift_date }} at {{ $shift_time }}.
    If you'd like reshedule, click here:
      <a href="{{ env('SITE_URL') . '/event/' . $slot->schedule->department->event->id }}">
        {{ env('SITE_NAME') }}
      </a>.
</p>
