@extends('partials/form/_bootstrap')

@section('html')
    @foreach($options as $option => $text)
        <div class="radio">
            <label>
                <input type="radio"
                        name="{{ $name }}"
                        id="{{ $name }}-field"
                        placeholder="{{ $placeholder or '' }}"
                        value="{{ $option }}"
                        {{ (isset($value) && $value == $option) ? 'selected' : '' }}>

                {{ $text }}
            </label>
        </div>
    @endforeach
@overwrite
