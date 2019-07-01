<h1>Daily Reminder about your shifts!</h1>

<p>
    Thank you for being a part of <b>{{ env('SITE_NAME') }}</b>.
    Here's your daily dump of some weird stuff.
</p>

@each()

@foreach($slot_metadata as $metadata)
    @include('emails/'.$metadata->layout, $metadata)
    <br>
@endforeach

<p>
    If any of these look strange, please <a href="{{ env('SITE_URL') }}">log into your account</a> and review your shifts.
</p>
