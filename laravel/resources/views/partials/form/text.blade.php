@extends('partials/form/_bootstrap')

@section('html')
    <input type="text"
            class="form-control"
            name="{{ $name }}"
            id="{{ $name }}-field"
            placeholder="{{ $placeholder or '' }}"
            value="{{ $value or '' }}">
@overwrite
