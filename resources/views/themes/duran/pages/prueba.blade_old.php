@extends('layouts.default')

@section('title')
	{{ trans($theme.'-app.head.title_app') }}
@stop




@section('content')

<link href='{{ URL::asset('vendor/year-calendar/jquery.bootstrap.year.calendar.css') }}' rel='stylesheet' />
<script src='{{ URL::asset('vendor/year-calendar/jquery.bootstrap.year.calendar.js') }}'></script>
@if( Config::get('app.locale') == 'es')
<style>
    .fc-toolbar { text-transform: capitalize; }
    .fc-day-header { text-transform: capitalize; }
</style>

<body>
        <div id='calendar'></div>
</body>
@endif
<script>

$(document).ready(function() {
    
    var cal = $('#calendar').calendar({
        cols: 6,
            colsSm: 6,
            colsMd: 6,
            colsLg: 4,
            colsXl: 4,
            startMonth: 10,
            minDay: new Date().getDay(),
            maxDaysToChoose: false,
    });

   
  });

</script>
@stop
