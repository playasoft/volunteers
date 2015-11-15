<?php

if(old('roles'))
{
    $roles = old('roles');
}
elseif(!isset($roles))
{
    $roles = [];
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
          <input type="checkbox" name="roles[]" value="admin" {{ in_array('admin', $roles) ? 'checked' : ''}}> Admin
        </label>

        <label>
          <input type="checkbox" name="roles[]" value="volunteer" {{ in_array('volunteer', $roles) ? 'checked' : ''}}> Volunteer
        </label>

        <label>
          <input type="checkbox" name="roles[]" value="veteran" {{ in_array('veteran', $roles) ? 'checked' : ''}}> Veteran
        </label>

        <label>
          <input type="checkbox" name="roles[]" value="fire" {{ in_array('fire', $roles) ? 'checked' : ''}}> Fire
        </label>

        <label>
          <input type="checkbox" name="roles[]" value="medical" {{ in_array('medical', $roles) ? 'checked' : ''}}> Medical
        </label>
      </div>
      
    @if($errors->has('roles'))
        <span class="help-block">{{ $errors->first('roles') }}</span>
    @endif
</div>
