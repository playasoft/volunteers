<h1>Uploaded File Denied</h1>

<p>
    A file you uploaded to <a href="https://env('SITE_URL')">{{ env('SITE_URL') }}</a> has been denied by an administrator.
    Please contact us for further information.
</p>

<li>
    File: <b>{{ $file->name }} - {{ $file->file }}</b>
</li>
