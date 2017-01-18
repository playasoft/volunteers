<h1>You forgot your password?</h1>

<p>
    Looks like you forgot your password for <b>{{ env('SITE_NAME') }}</b>. 
    If you forgot your password, you might have also forgotten your username.
    For the record, it's <b>{{ $user->name }}</b>.
    Now if you really want to reset your password you can click the link below to reset it: 
</p>

<p>
    <a href="{{ env('SITE_URL') }}/forgot/{{ $user->reset_token }}">{{ env('SITE_URL') }}/forgot/{{ $user->reset_token }}</a>
</p>

<p>
    If you didn't request your password to be reset, please contact an administrator.
</p>
