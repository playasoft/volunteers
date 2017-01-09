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
        placeholder="{{ $placeholder or '' }}"
        @if(isset($limit))
                maxlength="{{ $limit }}"
        @endif
        >{{ $value or '' }}</textarea>
@overwrite
