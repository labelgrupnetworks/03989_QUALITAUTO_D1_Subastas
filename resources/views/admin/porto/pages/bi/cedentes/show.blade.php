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

		<div class="col-xs-12 table-responsive">

			<table id="tableBiCedente" class="table table-striped display" style="width:100%">
				<thead>
					<tr>
						<th></th>
						<th>{{ 'nº de hoja' }}</th>
						<th>{{ 'nº Lots' }}</th>
						<th>{{ 'nº LotsInAuc' }}</th>
						<th>{{ 'nº LotsNoAuc' }}</th>
						<th>{{ 'Vendidos' }}</th>
						<th>{{ '% Vendidos' }}</th>
						<th>{{ '€ Salida tot.' }}</th>
						<th>{{ '€ Salida med.' }}</th>
						<th>{{ '€ Adj. tot.' }}</th>
						<th>{{ '€ Adj. avg.' }}</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th>{{ trans("admin-app.bi.total") }}</th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
					</tr>
				</tfoot>
			</table>

		</div>

	</div>

</section>

<input type="hidden" name="cedente_ajax" value='{{ route('bi_cedentes.show_data', ['cod_cli' => $cod_cli]) }}'>
{{-- <input type="hidden" name="clientesForDate" value='@json($clientesForDate)'> --}}

@stop
