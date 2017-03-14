<?php

if(old('roles'))
{
    $rolesArray = old('roles');
}
elseif(isset($roles))
{
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

        <label>
          <input type="checkbox" class="role" name="roles[]" value="admin" {{ in_array('admin', $rolesArray) ? 'checked' : ''}}> Admin
        </label>

        <label>
          <input type="checkbox" class="role" name="roles[]" value="volunteer" {{ in_array('volunteer', $rolesArray) ? 'checked' : ''}}> Volunteer
        </label>

        <label>
          <input type="checkbox" class="role" name="roles[]" value="ranger" {{ in_array('ranger', $rolesArray) ? 'checked' : ''}}> Ranger
        </label>

        <label>
          <input type="checkbox" class="role" name="roles[]" value="fire" {{ in_array('fire', $rolesArray) ? 'checked' : ''}}> Fire
        </label>

        <label>
          <input type="checkbox" class="role" name="roles[]" value="medical" {{ in_array('medical', $rolesArray) ? 'checked' : ''}}> Medical
        </label>
      </div>
      
    @if($errors->has('roles'))
        <span class="help-block">{{ $errors->first('roles') }}</span>
    @elseif(isset($help))
        <span class="help-block">{{ $help }}</span>
    @endif
</div>
