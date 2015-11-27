<?php

if(old($name))
{
    $value = old($name);
}

?>

@extends('partials/form/_bootstrap')

@section('html')
    <input type="time"
            class="form-control"
            name="{{ $name }}"
            id="{{ $name }}-field"
            placeholder="{{ $placeholder or 'hh:mm' }}"
            value="{{ $value or '' }}">
@overwrite
