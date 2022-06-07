@extends('admin::layouts.logged')
@section('content')

<section role="main" class="content-body">
	<section id="loader-page">
		<div class="lds-ripple">
			<div></div>
			<div></div>
		</div>
	</section>

	@include('admin::includes.header_content')

	@csrf

	<div class="row well header-well d-flex align-items-center">
		<div class="col-xs-12">
			<h1>{{ trans("admin-app.title.bi_long") }}</h1>
		</div>
	</div>

	<div class="row well">

		<div class="col-xs-12 text-right mb-1 pt-1 pb-1" style="background-color: #ffe7e7">

			{{-- exportar --}}
			{{-- <a id="clientesExport" href="{{ route('clientes.export') }}" class="btn btn-sm btn-primary">
				{{ trans('admin-app.button.export') }}
			</a> --}}

			{{-- configuracion de tabla --}}
			@include('admin::includes.config_table_v2', ['id' => 'bi_cedentes', 'params' => $tableParams, 'formulario' => $formulario])
		</div>


		<div class="col-xs-12 table-responsive">
			<table id="bi_cedentes" class="table table-striped table-condensed table-responsive" style="width:100%" data-order-name="order">
				<thead>
					<tr>
						@include('admin::components.tables.sortable_column_header', ['field' => 'cod_cli'])
						@include('admin::components.tables.sortable_column_header', ['field' => 'rsoc_cli'])
						@include('admin::components.tables.sortable_column_header', ['field' => 'pais_cli'])
						<th class="hces1_count"><span>{{ trans("admin-app.fields.hces1_count") }}</span></th>
						@include('admin::components.tables.sortable_column_header', ['field' => 'lotes_count'])
						<th class="lotes_withoutaucion_count"><span>{{ trans("admin-app.fields.lotes_withoutaucion_count") }}</span></th>
						<th>
							<span>{{ trans("admin-app.fields.actions") }}</span>
						</th>
					</tr>
				</thead>
				<tbody>

					{{-- <tr id="filters">
						<form class="form-group" action="">
							<input type="hidden" name="order" value="{{ request('order', 'lotes_count') }}">
							<input type="hidden" name="order_dir" value="{{ request('order_dir', 'desc') }}">

							@foreach ($tableParams as $param => $display)
							<td class="{{$param}}" @if(!$display) style="display: none" @endif>
								{!! $formulario->$param ?? '' !!}
							</td>
							@endforeach

							<td class="d-flex">
								<input type="submit" class="btn btn-info w-100"
									value="{{ trans("admin-app.button.search") }}">
									<a href="{{route('clientes.index')}}"
										class="btn btn-warning w-100">{{ trans("admin-app.button.restart") }}
									</a>
							</td>
						</form>
					</tr> --}}

					@forelse ($cedentes as $cedente)

					<tr id="{{$cedente->cod_cli}}">
						<td class="cod_cli" @if(!$tableParams['cod_cli']) style="display: none" @endif>{{$cedente->cod_cli}}</td>
						<td class="rsoc_cli" @if(!$tableParams['rsoc_cli']) style="display: none" @endif>{{$cedente->rsoc_cli}}</td>
						<td class="pais_cli" @if(!$tableParams['pais_cli']) style="display: none" @endif>{{$cedente->pais_cli}}</td>
						<td class="hces1_count" @if(!$tableParams['hces1_count']) style="display: none" @endif>
							{{$cedente->hojasCesion->unique('num_hces1')->count()}}
						</td>
						<td class="lotes_count" @if(!$tableParams['lotes_count']) style="display: none" @endif>
							{{$cedente->lotes_count }}
						</td>
						<td class="lotes_withoutaucion_count" @if(!$tableParams['lotes_withoutaucion_count']) style="display: none" @endif>
							{{$cedente->hojasCesion->where('ref_asigl0', null)->count()}}
						</td>
						<td><a href="{{ route('bi_cedentes.show', ['cod_cli' => $cedente->cod_cli]) }}" class="btn btn-info btn-sm"><i class="fa fa-eye"></i></a></td>
					</tr>

					@empty

					<tr>
						<td colspan="6">
							<h3 class="text-center">{{ trans("admin-app.title.without_results") }}</h3>
						</td>
					</tr>

					@endforelse
				</tbody>
			</table>

		</div>

	</div>

	<div class="col-xs-12 d-flex justify-content-center">
		{{ $cedentes->links() }}
	</div>


</section>

{{-- <input type="hidden" name="auctions_ajax" value='1'> --}}
{{-- <input type="hidden" name="clientesForDate" value='@json($clientesForDate)'> --}}

@stop
