@extends('layouts.default')

@section('title')
	{{ trans($theme.'-app.head.title_app') }}
@stop


@section('content')


<link href='{{ URL::asset('vendor/full-calendar/fullcalendar.min.css') }}' rel='stylesheet' />
<link href='{{ URL::asset('vendor/full-calendar/fullcalendar.print.min.css') }}' rel='stylesheet' media='print' />
<script src='{{ URL::asset('vendor/full-calendar/moment.min.js') }}'></script>
<script src='{{ URL::asset('vendor/full-calendar/fullcalendar.min.js') }}'></script>
@if( Config::get('app.locale') == 'es')
<script src='{{ URL::asset('vendor/full-calendar/locale/es.js') }}'></script>
<style>
    .fc-toolbar { text-transform: capitalize; }
    .fc-day-header { text-transform: capitalize; }
</style>
@endif
@php ($bread[] = array("name" =>trans($theme.'-app.home.calendar-news')))
<div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12 text-center color-letter">
                <h1 class="titlePage">{{ trans($theme.'-app.home.calendar-news') }}</h1>
                    @include('includes.breadcrumb')
                </div>
            </div>
        </div>
<div class="container content calendar-page">
    <div class="row">
        <div class='col-md-9'>
            <div id='calendar'></div>
             <div> <strong>* {{ trans($theme.'-app.calendar.holidays') }}</strong></div>
        </div>
        <div class='col-md-3'>
            @foreach($data as $sub)
                @if($sub->tipo_sub == 'W')
                <div class="bs-callout bs-callout-default" id="callout-alerts-dismiss-plugin" <?=!empty($sub->colorcal_sub)?'style="border-left-color: '.$sub->colorcal_sub.'"':''?>> 
                    <h5>{{$sub->des_sub}}</h5> 
                   
                    <p>{{ trans($theme.'-app.calendar.start_expo') }} {{ date("d/m/Y ", strtotime($sub->calini_sub))}} </p> 
                    <p>{{ trans($theme.'-app.calendar.end_expo') }} {{ date("d/m/Y ", strtotime($sub->calfin_sub))}} </p> 
                    
                    <p>{{ trans($theme.'-app.calendar.start_rt') }} {{ date("d/m/Y H:i:s", strtotime($sub->session_start))}} </p>                     
                </div>
                @endif
            @endforeach
            
        </div>
    </div>
</div>

 
 <script>
$(document).ready(function() {
    
    $('#calendar').fullCalendar({
      header: {
        left: 'prev',
        center: 'title',
        right: 'next '
      },
        defaultDate: '<?= date("Y-m-d")?>',
        eventLimit: true, // allow "more" link when too many events
        weekends: false, 
      eventRender: function(eventObj, $el) {
        $el.popover({
          title: eventObj.title,
          content: eventObj.description,
          trigger: 'hover',
          placement: 'top',
          container: 'body'
        });
      },
      events: [
        <?php foreach($data as $sub){
              if($sub->tipo_sub == 'W'){
                  ?>
            {
                title: '<?= $sub->des_sub ?>',
                start: '<?= date("Y-m-d",strtotime($sub->session_start)) ?>',
                end: '<?= date("Y-m-d",strtotime($sub->session_end)) ?>',
                color: '#D82F2F'
            },
                     {
                title: '<?= trans($theme.'-app.calendar.expo') ?> <?= $sub->des_sub ?>',
                start: '<?= date("Y-m-d",strtotime($sub->calini_sub)) ?>',
                end: '<?= date("Y-m-d",strtotime($sub->calfin_sub)) ?>',
                color: '<?= !empty($sub->colorcal_sub)?$sub->colorcal_sub:'#777' ?>'
            },
                   
        <?php
            }
          }
        ?>
      ]
    });

  });

</script>
@stop


