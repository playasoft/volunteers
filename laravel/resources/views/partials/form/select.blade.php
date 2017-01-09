<?php

if(old($name))
{
    $value = old($name);
}

?>

@extends('partials/form/_bootstrap')

@section('html')
    <select class="form-control {{ $class or '' }}" name="{{ $name }}" id="{{ $name }}-field">
        @foreach($options as $key => $option)
            <option value="{{ $key }}" {{ (isset($value) && $key == $value) ? 'selected' : '' }}>{{ $option }}</option>
        @endforeach
    </select>
@overwrite
