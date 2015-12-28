<h1>Uploaded File Approved</h1>

<p>
    A file you uploaded to <a href="https://env('SITE_URL')">{{ env('SITE_URL') }}</a> has been approved by an administrator.
    If necessary, your user role will now be updated to allow you to sign up for different shifts.
</p>

<li>
    File: <b>{{ $file->name }} - {{ $file->file }}</b>
</li>
