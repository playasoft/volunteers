<?php

if(old($name))
{
    $value = old($name);
}

?>

@extends('partials/form/_bootstrap')

@section('html')
    <textarea
        class="form-control"
        name="{{ $name }}"
        id="{{ $name }}-field"
        placeholder="{{ $placeholder ?? '' }}"
        @if(isset($limit))
                maxlength="{{ $limit }}"
        @endif
        >{{ $value ?? '' }}</textarea>
@overwrite
