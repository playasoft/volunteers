@extends('app')

@section('content')
<div class="days">
  <div class="day">
    @include('partials/timegrid/heading')
    @include('partials/timegrid/grid')
  </div>
</div>
@endsection
