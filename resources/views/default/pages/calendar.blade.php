@extends('layouts.default')

@section('title')
	{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop


@section('content')

@php ($bread[] = array("name" =>trans(\Config::get('app.theme').'-app.home.calendar-news'))) @endphp


<?php
$webCalendar = new  \App\Models\V5\WebCalendarEvent ();

$events = $webCalendar->get();
?>

<style>

.calendar .month{
	margin-top:10px;
}
.calendar .day-content{

	border-radius:0px !important;
}

@foreach($events as $event)
.{{$event->cod_calendar_event}}_calendar{
	background-color:{{$event->color_calendar_event}};

}
	{{-- pintamos del mismo color que la subasta el borde de la info de subasta --}}
	@if($event->cod_calendar_event == "AUCTION")
		.{{$event->cod_calendar_event}}_calendar_border{
			border-left-color: {{$event->color_calendar_event}};
		}
		@endif
@endforeach


.calendar .month-container{
	height:205px !important;
}
</style>

<div class="breadcrumb-total row">
  <div class="col-xs-12 col-sm-12 text-center color-letter">
    @include('includes.breadcrumb')
    <div class="container">
      <h1 class="titlePage">{{ trans(\Config::get('app.theme').'-app.home.calendar-news') }}</h1>
    </div>
  </div>
</div>
<?php
$webCalendar= new  \App\Models\V5\WebCalendar ();

#buscamos fechas a partir del mes actual, para no cargar nada que no sea necesario
$year = request("year",date("Y"));
#evitamos que nos rompan el calendario
if( is_numeric($year) && $year > 2000 & $year < 2100){
	$days = $webCalendar->where("start_calendar",">",date($year."-01-01"))->where("start_calendar","<=",date($year."-12-31 23:59:59"))->JoinEvent()->get();
	$eventInCalendar=array();
	foreach($days as $day){
		$eventInCalendar[$day->cod_calendar_event] = 1;
	}
}

?>
@if( is_numeric($year) && $year > 2000 & $year < 2100)
<div class="container content calendar-page">
    <div class="row">
		<div class="col-md-9 text-center" >
			<a class="previous_year" href="?year={{$year-1}}"  > {{ trans(\Config::get('app.theme').'-app.calendar.year') }} {{$year-1}} </a>
			<span class="actual_year"> {{$year}} </span>
			<a class="next_year" href="?year={{$year+1}}"> {{ trans(\Config::get('app.theme').'-app.calendar.year') }} {{$year+1}} </a>
		</div>
		<div class='col-md-3'>
		</div>
        <div class='col-md-9'>
            <div id='calendar'></div>
             <div> <strong> {{ trans(\Config::get('app.theme').'-app.calendar.holidays') }}</strong></div>
        </div>
		<div class='col-md-3'>
			@foreach($events as $event)
				@if ( array_key_exists($event->cod_calendar_event,$eventInCalendar))
					<p> <span class="calendar_event_label" style="background-color:{{$event->color_calendar_event}}"> </span> {{ trans(\Config::get('app.theme').'-app.calendar.'.$event->cod_calendar_event.'_event') }} </p>
				@endif
			@endforeach
			@php
			#evitamos que se repitan sesiones con misma fecha
			 $fechasSesiones = array()
			 @endphp
            @foreach($auctions as $sub)
				@if($sub->tipo_sub == 'W' &&  !array_key_exists(strtotime($sub->session_start), $fechasSesiones)  )

					<div class="bs-callout bs-callout-default AUCTION_calendar_border" id="callout-alerts-dismiss-plugin" >
						<h5>{{$sub->des_sub}}</h5>
						@if (!empty($sub->calini_sub))
							<p>{{ trans(\Config::get('app.theme').'-app.calendar.start_expo') }} {{ date("d/m/Y ", strtotime($sub->calini_sub))}} </p>
						@endif
						@if (!empty($sub->calfin_sub))
							<p>{{ trans(\Config::get('app.theme').'-app.calendar.end_expo') }} {{ date("d/m/Y ", strtotime($sub->calfin_sub))}} </p>
						@endif
						@if (!empty($sub->session_start))
							<p>{{ trans(\Config::get('app.theme').'-app.calendar.start_rt') }} {{ date("d/m/Y H:i:s", strtotime($sub->session_start))}} </p>
						@endif
					</div>
					@php  $fechasSesiones[strtotime($sub->session_start)] = 1 @endphp
                @endif
            @endforeach

        </div>
    </div>
</div>



<script>



	$( document ).ready(function() {

	var currentYear ="{{ date("Y")}}";
			var cal = $('#calendar').calendar({
				language: '{{ Config::get('app.locale') }}',
				enableContextMenu: true,
				enableRangeSelection: true,
				startMonth: 1,
				minDay: new Date().getDay(),
				maxDaysToChoose: false,
				startYear: <?= $year ?>,
			    dataSource:[
				@foreach($days as $day)
					@php
						$start = strtotime($day->start_calendar);
						$end = strtotime($day->end_calendar);

					@endphp
					{
						id: "{{$day->id_calendar}}",
						description: "{{$day->description_calendar}}",
						startDate: new Date( "{{date('Y', $start)}}", "{{date('m', $start) -1 }}", "{{date('d', $start)}}"),
						endDate: new Date( "{{date('Y', $end)}}", "{{date('m', $end) -1 }}", "{{date('d', $end)}}"),
						color: "{{$day->color_calendar_event}}",
						url: "{{$day->url_calendar}}",

					},
				@endforeach
			],
			/* Para que color pinte el fondo */
			style: 'background',
				customDayRenderer: function(element, date) {
					/* pintar fin de semana */
					if (date.getDay() === 6 || date.getDay() === 0) {
						/* lo añadimso al padre para asi dejar poner mas clases si cae un dia especial */
						$(element).parent().addClass('CLOSE_calendar');
					}
				},
				/* Poner enlace en el día si tiene url */
			clickDay: function(el ){
                if(el.events.length > 0){

                    if(el.events[0].url !== ''){

                        url = el.events[0].url
					   console.log(url);
					   window.location.href= url;
                    }


                }

			},

			/* Mostrar alt con datos del dia */
			mouseOnDay: function(e) {
				if(e.events.length > 0) {
					var content = '';

					for(var i in e.events) {
						content += '<div class="event-tooltip-content">'
										+ '<div class="event-name" style="color:' + e.events[i].color + '">' + e.events[i].description + '</div>'

									+ '</div>';
					}

					$(e.element).popover({
						trigger: 'manual',
						container: 'body',
						html:true,
						content: content
					});

					$(e.element).popover('show');
				}
			},
			mouseOutDay: function(e) {
				if(e.events.length > 0) {
					$(e.element).popover('hide');
				}
			}

		});




	});
	</script>
@endif
@stop


