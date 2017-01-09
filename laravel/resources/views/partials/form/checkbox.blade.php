@extends('partials/form/_bootstrap')

@section('html')
    @foreach($options as $option)
        <div class="checkbox">
            <label>
                <input type="checkbox"
                        name="{{ $name }}"
                        id="{{ $name }}-field"
                        placeholder="{{ $placeholder or '' }}"
                        value="1"
                        {{ (isset($value) && $value) ? 'checked' : '' }}>

                {{ $option }}
            </label>
        </div>
    @endforeach
@overwrite
