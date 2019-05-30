<h1>Hi {{ $slot->user->name }}!</h1>

<p>
    It seems you've been effected by a recent scheduling change for {{ $slot->schedule->shift->name }}!
    If you'd like reshedule, click here:
      <a href="{{ env('SITE_URL').'/event/'.$slot->schedule->department->event->id }}">
        {{ env('SITE_NAME') }}
      </a>.
</p>
