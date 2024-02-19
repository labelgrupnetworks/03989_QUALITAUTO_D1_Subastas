@extends('layouts.default')

@section('title')
	{{ trans($theme.'-app.head.title_app') }}
@stop

@push('stylesheets')
	<link rel="stylesheet" href="https://unpkg.com/js-year-calendar@2.0.0/dist/js-year-calendar.min.css">
@endpush

@push('scripts')
<script src="https://unpkg.com/js-year-calendar@latest/dist/js-year-calendar.min.js"></script>
<script src="https://unpkg.com/js-year-calendar@latest/locales/js-year-calendar.es.js"></script>
@endpush

@section('content')

@php
	$bread[] = array("name" =>trans($theme.'-app.home.calendar-news'));
@endphp

<main>
	<div class="container">
	<div class="row">
		<div class="col-12">
			@include('includes.breadcrumb')
		</div>
		<div class="col-12">
			<h1 class="titlePage">{{ trans($theme.'-app.home.calendar-news') }}</h1>
		</div>
	</div>

	<div class="row">

		<div class="col-12 col-md-9">
			<div class="select-year d-flex justify-content-between">
				<a class="previous_year text-decoration-none" href="?year={{$year - 1}}"  > {{ trans($theme.'-app.calendar.year') }} {{$year - 1}} </a>
				<h3 class="actual_year"> {{$year}} </h3>
				<a class="next_year text-decoration-none" href="?year={{$year + 1}}"> {{ trans($theme.'-app.calendar.year') }} {{$year + 1}} </a>
			</div>

			<div id='calendar'></div>

			<p>
				<strong> {{ trans($theme.'-app.calendar.holidays') }}</strong>
			</p>
		</div>

		<div class='col-12 col-md-3'>
			@foreach($events as $event)
				@if (array_key_exists($event->cod_calendar_event, $eventInCalendar))
					<p>
						<span class="calendar_event_label" style="background-color:{{$event->color_calendar_event}}"></span>
						<span>{{ trans("$theme-app.calendar.{$event->cod_calendar_event}_event") }}</span>
					</p>
				@endif
			@endforeach

			@php
			#evitamos que se repitan sesiones con misma fecha
			$fechasSesiones = array()
			@endphp

			@foreach($auctions as $sub)
				@if($sub->tipo_sub == 'W' && !array_key_exists(strtotime($sub->session_start), $fechasSesiones))

					<div class="bs-callout bs-callout-default AUCTION_calendar_border" id="callout-alerts-dismiss-plugin" >
						<h5>{{$sub->des_sub}}</h5>
						@if (!empty($sub->calini_sub))
							<p>{{ trans($theme.'-app.calendar.start_expo') }} {{ date("d/m/Y ", strtotime($sub->calini_sub))}} </p>
						@endif
						@if (!empty($sub->calfin_sub))
							<p>{{ trans($theme.'-app.calendar.end_expo') }} {{ date("d/m/Y ", strtotime($sub->calfin_sub))}} </p>
						@endif
						@if (!empty($sub->session_start))
							<p>{{ trans($theme.'-app.calendar.start_rt') }} {{ date("d/m/Y H:i:s", strtotime($sub->session_start))}} </p>
						@endif
					</div>
					@php
						$fechasSesiones[strtotime($sub->session_start)] = 1;
					@endphp
				@endif
			@endforeach
		</div>
	</div>

	</div>
</main>

<script>
	const auctionEvents = @json($auctionsEventsFormat);
	const daysEvents = @json($daysEventsFormat);
	$(function() {
		calendarInitialize(auctionEvents, daysEvents);
	});
</script>
@stop


