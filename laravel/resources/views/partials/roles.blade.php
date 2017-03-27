<?php

// Get all roles
use App\Models\Role;
$allRoles = Role::get();

if(old('roles'))
{
    $rolesArray = old('roles');
}
elseif(isset($roles))
{
    // If roles were passed to this partial, create a roles array
    $rolesArray = [];

    foreach($roles as $role)
    {
        $rolesArray[] = $role->role->name;
    }
}
else
{
    $rolesArray = [];
}

?>

<div class="form-group {{ ($errors->has('roles')) ? 'has-error' : '' }}">
    <label class="control-label" for="roles-field">Allowed User Groups</label>

      <div class="checkbox">
        <label>
          <input type="checkbox" class="roles-all" value="all"> All
        </label>

        <label>
          <input type="checkbox" class="roles-none" value="none"> None
        </label>

        @foreach($allRoles as $role)
            <label>
              <input type="checkbox" class="role" name="roles[]" value="{{ $role->name }}" {{ in_array($role->name, $rolesArray) ? 'checked' : ''}}> {{ ucwords(str_replace('-', ' ', $role->name)) }}
            </label>
        @endforeach
      </div>
      
    @if($errors->has('roles'))
        <span class="help-block">{{ $errors->first('roles') }}</span>
    @elseif(isset($help))
        <span class="help-block">{{ $help }}</span>
    @endif
</div>
