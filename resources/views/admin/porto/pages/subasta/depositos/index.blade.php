@extends('admin::layouts.logged')
@section('content')

<section role="main" class="content-body">
	@include('admin::includes.header_content')
	@csrf

	<div class="row well header-well d-flex align-items-center">
		<div class="col-xs-9">
			<h1>{{ trans("admin-app.title.deposits") }}</h1>
		</div>
		<div class="col-xs-3">
			<div class="btn-group" id="js-dropdownItems">
				<button class="btn btn-default btn-sm" type="button">Seleccionados</button>
				<button
					data-objective="desposit_ids"
					class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" type="button"
					aria-haspopup="true" aria-expanded="false">
					<span class="caret"></span>
				</button>

				<ul aria-labelledby="js-dropdownItems" class="dropdown-menu">

					<li>
						<button class="btn" data-toggle="modal" data-target="#editMultpleDepositsModal">
							Modificar
						</button>
					</li>

				</ul>
			</div>

			<a href="{{ route('deposito.create', ['menu' => 'subastas']) }}" class="btn btn-primary right">{{ trans("admin-app.button.new") }} {{ trans("admin-app.title.deposit") }}</a>
		</div>
	</div>

	<div class="row well">

		<div class="col-xs-12">
			<table id="" class="table table-striped table-condensed table-responsive" style="width:100%">
				<thead>

					<tr>
						<th>
							<label>
								<input id="selectAllDeposits" name="js-selectAll" data-objective="desposit_ids" type="checkbox" value="true">
								<input id="urlAllSelected" name="url-allSelected"  type="hidden" value="{{ route('subastas.deposit.ajax_get_deposits') }}">
							</label>
						</th>
						<th>{{ trans("admin-app.fields.sub_deposito") }}</th>
						<th>{{ trans("admin-app.fields.ref_deposito") }}</th>
						<th>{{ trans("admin-app.fields.rsoc_cli") }}</th>
						<th>{{ trans("admin-app.fields.estado_deposito") }}</th>
						<th>{{ trans("admin-app.fields.importe_deposito") }}</th>
						<th>{{ trans("admin-app.fields.fecha_deposito") }}</th>
						<th>{{ trans("admin-app.fields.cli_deposito") }}</th>
						<th>{{ trans("admin-app.fields.actions") }}</th>
					</tr>
				</thead>

				<tbody>

					<tr id="filters">
						<form class="form-group" action="">
							<td></td>
							<td>{!! $formulario->sub_deposito !!}</td>
							<td>{!! $formulario->ref_deposito !!}</td>
							<td>{!! $formulario->rsoc_cli !!}</td>
							<td>{!! $formulario->estado_deposito !!}</td>
							<td>{!! $formulario->importe_deposito !!}</td>
							<td>{!! $formulario->fecha_deposito !!}</td>
							<td>{!! $formulario->cli_deposito !!}</td>
							<td><input type="submit" class="btn btn-info w-100" value="{{ trans("admin-app.button.search") }}"><a href="{{route('deposito.index', ['menu' => 'subastas'])}}" class="btn btn-warning w-100">{{ trans("admin-app.button.restart") }}</a></td>
						</form>
					</tr>

					@forelse ($fgDepositos as $deposito)

					<tr id="fila{{$deposito->cod_deposito}}">
						<td>
							<label>
								<input type="checkbox" name="desposit_ids" value="{{$deposito->cod_deposito}}">
							</label>
						</td>
						<td>{{$deposito->sub_deposito}}</td>
						<td>{{$deposito->ref_deposito}}</td>
						<td>{{$deposito->rsoc_cli}}</td>
						<td>{{$deposito->estado}}</td>
						<td>{{$deposito->importe_deposito}}</td>
						<td>{{$deposito->fecha_deposito}}</td>
						<td>{{$deposito->cli_deposito}}</td>

						<td>
							<a href="{{ route('deposito.edit', $deposito->cod_deposito) }}" class="btn btn-primary btn-sm">{{ trans("admin-app.button.edit") }}</a>
							{{--<a href="javascript:borrarDeposito('{{$deposito->cod_deposito}}');"
								class="btn btn-danger btn-sm">Borrar</a>--}}
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
		<div class="col-xs-12 d-flex justify-content-center">
			{{ $fgDepositos->links() }}
		</div>
	</div>

	@include('admin::pages.subasta.depositos._edit_selecteds')

	@stop
