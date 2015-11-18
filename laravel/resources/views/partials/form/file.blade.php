@extends('partials/form/_bootstrap')

@section('html')
    <div>
        <span class="btn btn-primary btn-file">
            <input type="file" name="{{ $name }}" id="{{ $name }}-field">
        </span>

        @if(!empty($value))
            &emsp;<span>Current file: {{ $value }}</span>
        @endif
    </div>
@overwrite
