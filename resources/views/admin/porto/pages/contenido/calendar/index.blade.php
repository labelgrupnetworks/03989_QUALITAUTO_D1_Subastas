@extends('admin::layouts.logged')
@section('content')

<section role="main" class="content-body">

	<div id="calendar">

		<a href="/admin/calendar/edit" class="btn btn-primary" style="float:right">{{ trans('admin-app.title.new') }}</a>

		<h1>{{ trans('admin-app.title.calendar') }}</h1>
		<br>


		<div class="row">
			<div class="col-12 col-md-1 ">
				<b>ID</b>
			</div>
			<div class="col-12 col-md-3 ">
				<b>{{  mb_strtoupper (trans('admin-app.title.nombre')) }}</b>
			</div>
			<div class="col-12 col-md-2">
				<b>{{  mb_strtoupper (trans('admin-app.placeholder.type')) }}</b>
			</div>
			<div class="col-12 col-md-2">
				<b>{{  mb_strtoupper (trans('admin-app.title.start')) }}</b>
			</div>
			<div class="col-12 col-md-2">
				<b>{{  mb_strtoupper (trans('admin-app.title.end')) }}</b>
			</div>
			<div class="col-12 col-md-2 ">
				<b>{{  mb_strtoupper (trans('admin-app.title.options')) }}</b>
			</div>
		</div>

		@foreach($events as $item)

		<div class="row items">
			<div class="col-12 col-md-1 ">
				{{ $item->id_calendar }}
			</div>
			<div class="col-12 col-md-3 ">
				{{ $item->name_calendar }}
			</div>
			<div class="col-12 col-md-2">
				{{ $item->cod_calendar_event }}
			</div>
			<div class="col-12 col-md-2">
				{{ date("d/m/Y", strtotime($item->start_calendar)) }}
			</div>
			<div class="col-12 col-md-2">
				{{ date("d/m/Y", strtotime($item->end_calendar)) }}
			</div>
			<div class="col-12 col-md-1 ">

				<a href="/admin/calendar/edit?id={{ $item->id_calendar }}" class="btn btn-primary">{{  trans('admin-app.title.edit') }}</a>


			</div>
			<div class="col-12 col-md-1 ">
				<form  method="post" action="/admin/calendar/delete">
					<button class="delete_button_js btn btn-danger" type="button">{{  trans('admin-app.title.delete') }}</button>
					<input type="hidden" name="idCalendar" value="{{ $item->id_calendar }}">
				</form>
			</div>
		</div>

		@endforeach
		{{ $paginator->links() }}
	</div>

@stop
