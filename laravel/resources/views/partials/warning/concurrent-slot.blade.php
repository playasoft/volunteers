<span>
@if(isset($admin) && $admin === true)
    {{ $user_name }} is currently signed up for another
    <a href=" {{ env('SITE_URL') }} /slot/{{ $concurrent_slot_id }}/view">overlapping shift</a>.
    Are you sure you want to sign them up for this shift?
@else
    You are currently signed up for another
    <a href=" {{ env('SITE_URL') }} /slot/{{ $concurrent_slot_id }}/view">overlapping shift</a>.
    Are you sure you want to sign up for this shift?
@endif
</span>
