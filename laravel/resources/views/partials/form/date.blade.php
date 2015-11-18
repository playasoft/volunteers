@extends('partials/form/_bootstrap')

@section('html')
    <input type="date"
            class="form-control"
            name="{{ $name }}"
            id="{{ $name }}-field"
            placeholder="{{ $placeholder or 'yyyy-mm-dd' }}"
            value="{{ $value or '' }}">
@overwrite
