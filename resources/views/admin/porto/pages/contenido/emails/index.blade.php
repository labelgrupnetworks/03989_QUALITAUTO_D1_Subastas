@extends('admin::layouts.logged')
@section('content')

<section role="main" class="content-body">
	@include('admin::includes.header_content')
	@csrf

	<div class="row well header-well d-flex align-items-center">
		<div class="col-xs-9">
			<h1 class="m-0">Emails</h1>
		</div>
	</div>

	<div class="row well">

		<div class="col-xs-12">
			@csrf
			<table id="" class="table table-striped table-condensed table-responsive" style="width:100%">
				<thead>

					<tr>
						<th>{{ trans("admin-app.fields.cod_email") }}</th>
						<th>{{ trans("admin-app.fields.des_email") }}</th>
						<th>{{ trans("admin-app.fields.subject_email") }}</th>
						<th>{{ trans("admin-app.fields.type_email") }}</th>
						<th>{{ trans("admin-app.fields.actions") }}</th>
					</tr>
				</thead>

				<tbody>

					@forelse ($emails as $email)

					<tr id="fila_{{$email->cod_email}}">
						<td>{{$email->cod_email}}</td>
						<td>{{$email->des_email}}</td>
						<td>{{$email->subject_email}}</td>


						@switch($email->type_email)
							@case('A')
							<td>Administrador</td>
								@break
							@case('L')
							<td>Licitante</td>
								@break
							@case('P')
							<td>Propietario</td>
								@break
							@default
							<td>{{$email->type_email}}</td>
						@endswitch


						<td class="d-flex w-100 gap-5">
							<a href="{{route('emails.edit', mb_strtolower($email->cod_email))}}" class="btn btn-primary btn-xs mr-1">
								<i class="fa fa-pencil"></i>
							{{-- <a href="{{ route('artist.edit', $artist->id_artist) }}" class="btn btn-primary btn-sm btn-block mt-0">{{ trans("admin-app.button.edit") }}</a> --}}
						</td>
					</tr>

					@empty

					<tr>
						<td colspan="6"><h3 class="text-center">{{ trans("admin-app.title.without_results") }}</h3></td>
					</tr>

					@endforelse
				</tbody>
			</table>

		</div>
	</div>

	@stop
