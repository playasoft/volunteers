<h1>New file uploaded!</h1>

<p>
    A new file has been uploaded on the Volunteer Database.
</p>

<ul>
    <li>
        Username: <b>{{ $file->user->name }}</b>
    </li>

    <li>
        File: <b>{{ $file->name }} - {{ $file->file }}</b>
    </li>
</ul>
