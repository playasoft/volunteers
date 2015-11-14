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
          <input type="checkbox" name="roles[]" value="volunteer"> Admin
        </label>

        <label>
          <input type="checkbox" name="roles[]" value="volunteer"> Volunteer
        </label>

        <label>
          <input type="checkbox" name="roles[]" value="volunteer"> Veteran
        </label>

        <label>
          <input type="checkbox" name="roles[]" value="volunteer"> Fire
        </label>

        <label>
          <input type="checkbox" name="roles[]" value="volunteer"> Medical
        </label>
      </div>
      
    @if($errors->has('roles'))
        <span class="help-block">{{ $errors->first('roles') }}</span>
    @endif
</div>
