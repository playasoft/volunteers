<?php

if(old($name))
{
    $selected = old($name);
}

?>

@extends('partials/form/_bootstrap')

@section('html')
    @foreach($options as $value => $option)
        <div class="checkbox">
            <label>
                <input type="checkbox"
                        name="{{ $name }}[]"
                        id="{{ $name }}-field"
                        placeholder="{{ $placeholder or '' }}"
                        value="{{ $value }}"
                        {{ (!empty($selected) && in_array($value, $selected)) ? 'checked' : '' }}>
                {{ $option }}
            </label>
        </div>
    @endforeach
@overwrite
