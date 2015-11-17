<?php

if(old($name))
{
    $value = old($name);
}

?>

<div class="form-group {{ ($errors->has($name)) ? 'has-error' : '' }}">
    <label class="control-label" for="{{ $name }}-field">{{ $label }}</label>
    <input type="time" class="form-control" name="{{ $name }}" id="{{ $name }}-field" placeholder="{{ $placeholder or 'hh:mm' }}" value="{{ $value or '' }}">

    @if($errors->has($name))
        <span class="help-block">{{ $errors->first($name) }}</span>
    @elseif(isset($help))
        <span class="help-block">{{ $help }}</span>
    @endif
</div>
