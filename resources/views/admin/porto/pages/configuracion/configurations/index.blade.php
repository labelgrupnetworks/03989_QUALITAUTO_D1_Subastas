@extends('admin::layouts.logged')
@section('content')

<section role="main" class="content-body">
		@csrf

	<div class="row well header-well d-flex align-items-center">
		<div class="col-xs-9">
			<h1 class="m-0">Configuración</h1>
		</div>
	</div>

	<div class="row well">

		<div class="col-xs-12">
			<div class="mb-2">
				<a href="{{ route('admin.configurations.resume') }}" class="btn btn-primary">
					Ver resumen de configuraciones modificadas
				</a>
			</div>
			@csrf
			<table id="" class="table table-striped table-bordered table-responsive" style="width:100%">
				<thead>

					<tr>
						<th>Sección</th>
						<th>Descripción</th>
						<th>{{ trans("admin-app.fields.actions") }}</th>
					</tr>
				</thead>

				<tbody>

					@foreach ($sections as $section)

					<tr>
						<td>{{$section}}</td>
						<td>{{ trans("admin-app.config.$section") }}</td>

						<td class="d-flex w-100 gap-5">
							<a href="{{ route('admin.configurations.show', $section) }}"
								class="btn btn-primary btn-xs mr-1">
								<i class="fa fa-eye"></i>
							</a>
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>

		</div>
	</div>

	@stop
