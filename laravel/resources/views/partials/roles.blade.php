<?php

// Get all roles
use App\Models\Role;
$roles = Role::get();

// Ignore certain roles from being displayed
$ignored = ['admin', 'banned', 'volunteer', 'event-admin'];

if(old('roles'))
{
    $selectedArray = old('roles');
}
elseif(isset($selected))
{
    // If roles were passed to this partial, create a roles array
    $selectedArray = [];

    foreach($selected as $select)
    {
        $selectedArray[] = $select->role->name;
    }
}
else
{
    $selectedArray = [];
}

?>

<div class="roles form-group {{ ($errors->has('roles')) ? 'has-error' : '' }}">
    <label class="control-label" for="roles-field">Does this shift require training?</label>

      <div class="checkbox">
        <label>
            <input type="checkbox" class="roles-none" name="roles[]" value="volunteer" {{ empty($selectedArray) || in_array('volunteer', $selectedArray) ? 'checked' : '' }}> None
        </label>

        @foreach($roles as $role)
            <?php if(in_array($role->name, $ignored)) continue; ?>

            <label>
                <input type="checkbox" class="role" name="roles[]" value="{{ $role->name }}" {{ in_array($role->name, $selectedArray) ? 'checked' : ''}}> {{ ucwords(str_replace('-', ' ', $role->name)) }}
            </label>
        @endforeach
      </div>
      
    @if($errors->has('roles'))
        <span class="help-block">{{ $errors->first('roles') }}</span>
    @elseif(isset($help))
        <span class="help-block">{{ $help }}</span>
    @endif
</div>
