@extends('partials/form/_bootstrap')

@section('html')
    <input type="password"
            class="form-control"
            name="{{ $name }}"
            id="{{ $name }}-field"
            placeholder="{{ $placeholder or '' }}"
            value="{{ $value or '' }}"
            @if(isset($limit))
                maxlength="{{ $limit }}"
            @endif
            >
@overwrite
