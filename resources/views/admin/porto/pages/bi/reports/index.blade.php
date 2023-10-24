@extends('admin::layouts.logged')
@section('content')

<section role="main" class="content-body">
	<section id="loader-page">
		<div class="lds-ripple"><div></div><div></div></div>
	</section>

	@include('admin::includes.header_content')

	@csrf

	<div class="row well header-well d-flex align-items-center">
		<div class="col-xs-12">
			<h1>{{ trans("admin-app.title.bi_reports") }}: {{$report["name"]}}</h1>
		</div>
	</div>



	<div class="row well">
		<div class="col-xs-12">
			<div class="row row-reports">
				{{-- MENU --}}
				<div class="col-xs-2 menu-reports" >

						<center><h3>{{ trans("admin-app.reportsBi.menuTitle") }}</h3></center>
						<ul >

							<li><a href="/admin/bi/report/categoryAwardsSales?years[]={{date("Y")}}"><i class="fa fa-bar-chart" aria-hidden="true"></i> {{ trans("admin-app.reportsBi.reports.sale_category") }}  </a></li>
							<li><a href="/admin/bi/report/categoryAwardsAmount?years[]={{date("Y")}}"><i class="fa fa-bar-chart" aria-hidden="true"></i> {{ trans("admin-app.reportsBi.reports.amount_sale_category") }}  </a></li>
							<li><a href="/admin/bi/report/auctionAwardsSales?years[]={{date("Y")}}"><i class="fa fa-bar-chart" aria-hidden="true"></i> {{ trans("admin-app.reportsBi.reports.sale_auction") }}  </a></li>
							<li><a href="/admin/bi/report/auctionAwardsAmount?years[]={{date("Y")}}"><i class="fa fa-bar-chart" aria-hidden="true"></i> {{ trans("admin-app.reportsBi.reports.amount_sale_auction") }}  </a></li>
							<li><a href="/admin/bi/report/bidders?years[]={{date("Y")}}"><i class="fa fa-bar-chart" aria-hidden="true"></i> {{ trans("admin-app.reportsBi.reports.bidders") }}  </a></li>
							<li><a href="/admin/bi/report/typeBids?years[]={{date("Y")}}"><i class="fa fa-bar-chart" aria-hidden="true"></i> {{ trans("admin-app.reportsBi.reports.type_bids") }}  </a></li>

							<li><a href="/admin/bi/report/catalogClients?years[]={{date("Y")}}"><i class="fa fa-bar-chart" aria-hidden="true"></i> {{ trans("admin-app.reportsBi.reports.catalog_subscribed") }}  </a></li>

						</ul>


				</div>
				{{--FIN  MENU --}}
				{{-- FILTROS--}}
				<div class="p-1" style="background-color: #ffe7e7; display: flex; align-items: end; gap: 1rem">
					<form action="{{ route('bi_report',["report"=>request("report")]) }}" name="biReload" id="biReload" style="flex: 1; display: flex; align-items: end; justify-content: space-between; gap: 1rem">
						@foreach ($formulario as $field => $input)
							<div style="flex: 1">
								<label class="mt-1" for="{{$field}}">{{ trans("admin-app.fields.$field") }}</label>

								{!! $input !!}
							</div>
						@endforeach

						<button type="submit" class="btn btn-sm btn-default">{{ trans("admin-app.button.search") }}</button>
						{{-- <div class="col-xs-2 ">
							<div class="col-xs-6">
								<br>
								No pongo el botón de reiniciar por que hay queries pesadas que si se piden por todos los años tardan mucho
									<button onclick="refreshAll()" class="btn btn-default">{{ trans("admin-app.button.restart") }}</button>

							</div>
						</div> --}}
					</form>

					<button class="btn btn-sm btn-warning" onclick="window.print()">Imprimir</button>

					<a href="{{Request::fullUrlWithQuery(["export" => "excel"])}}" target="_blank" class="btn btn-sm btn-primary">Exportar EXCEL</a>

				</div>
				{{-- FIN FILTRO --}}

				<div class="col-xs-10 body-reports">
				@foreach ($report["charts"] as $keyChart =>$chart)
						@foreach ($chart->types as $type)
						{{-- si le indicamos que es un gráfico de una columna ocupara todo el ancho--}}
							<div class="col-xs-12  col-md-{{ 12/$chart->columns}} mt-2 mb-2">
								<h2 class="text-center"> {{$chart->name}}</h2>
								@if (count($chart->datasets) ==0)
									<div class="col-xs-12 mt-2 mb-2">	<center> SIN DATOS  </center></div>
								@endif
								{{-- si le indicamos que es un gráfico de una columna debemos darle una altura de 100 para que no sea muy alto--}}
								<canvas id="report_{{$keyChart}}_{{$type}}" width="400" height="{{ ($chart->columns==1)?  "100" : "400"  }}" ></canvas>
							</div>
						@endforeach

				@endforeach

				@if (!empty($report["datatable"]))
					<div class="col-xs-12 mt-3 mb-3">
						<center><h2>{{$report["name"]}}</h2></center>
						<table id="dtreports"  style="width:100%;text-align:center">


							<tfoot>
								<tr role="row">
									@if (!empty($report["subtitles"]))
									{{-- ponemos el campo vacio para ocupar el sitio de los botones desplegables de las subtablas --}}
										<th > </th>
									@endif
									@foreach($report["titles"] as $key=> $title)
										<th > {{$report["totales"][$key]}}</th>
									@endforeach
								</tr>
							</tfoot>
						</table>
					</div>
				@endif
			</div>
			</div>
		</div>
	</div>



</section>


<script>

$(document).ready(function () {


	function initMultiSelects() {

		//const multiSelect = $('[name="auctions[]"]');
		const multiSelect = $('select');
		multiSelect.multiselect({
			buttonWidth : '100%',
			maxHeight: 400,
			enableFiltering: true

		});

		$('.multiselect-filter i').addClass('fa');
		$('.multiselect-container .multiselect-filter', $('select').parent()).css({
			'position': 'sticky', 'top': '0px', 'z-index': 1,
		});
	return true;
	}
	initMultiSelects();


	Chart.register(ChartDataLabels);


	@foreach ($report["charts"] as $keyChart =>$chart)
	@php $rightYAxis = false ; @endphp
		@foreach ($chart->types as $type)
			const ctx_{{$keyChart}}_{{$type}} = document.getElementById('report_{{$keyChart}}_{{$type}}');

			new Chart(ctx_{{$keyChart}}_{{$type}} , {
			type: '{{$type}}',
			data: {
				labels: ['{!! implode("','",$chart->labels) !!}'],
				datasets: [
				@foreach($chart->datasets as  $key =>$dataset)
					@php $color = $chart->getColors($type) ; @endphp
					{
						label: '{{$dataset["title"]}}',
						data: [{{implode(",",$dataset["data"])}} ],
						borderWidth: 2,

						backgroundColor: [{!! $color  !!}],
						/* usamos type para algunos graficos especiales como por ejemplo poner un line con los de barras   */


						@if (empty($dataset["type"]))
							yAxisID: 'left-y-axis'
						@else
							@php $rightYAxis = true ; @endphp
							type: '{{$dataset["type"]}}',
							borderColor: {!!$color!!},

							tension: 0.4,
							yAxisID: 'right-y-axis'


						@endif

					},
				@endforeach

			]
			},
			options: {
				scales: {
					'left-y-axis': {
						type: 'linear',
						position: 'left'
					},
				@if( $rightYAxis)
					'right-y-axis': {
						type: 'linear',
						position: 'right'
					}
				@endif
				},
				plugins: {


					datalabels: {



							formatter: function (value) {
								// quitamos los decimales y convertimso en string para poder  poner los puntos de millar

								k="";

								if(value > 10000){
									value= value/1000
									k="K";
								}
								value= Math.round(value)+ "";

								value = value.replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1.") + k

								return value
							},
							color: 'black',

							font: {
							weight: 'bold'
							},

						}


				}

			}
		});


		@endforeach

	@endforeach
@if (!empty($report["datatable"]))

	dataForTable=[
		@foreach ($report["datatable"] as $a)
			@json($a),
		@endforeach
	]



	table = $('#dtreports').DataTable({

		paging:{{ !empty($report["pagingDatatable"])? "true" : "false" }},
        ordering: true,
        info: false,
		searching: true,

        data: dataForTable,

        columns: [
			@if (!empty($report["subtitles"]))
			/* boton que despliega los sublistados si existen */
				{
					"className":      'details-control',
					"orderable":      false,
					"data":           null,
					"defaultContent": ''
				},
			@endif
			/*  en el array title tenemos los titulos y el nombre de los campos, hay un array para marcar los anchos  */
			@foreach ($report["titles"] as $key => $value)
				{
				@if(!empty($report["width"]) )
					 width: '{{$report["width"][$key]}}',
				@endif

				@if(!empty($report["classes"]))
					className:'{{$report["classes"][$key]}}',
				@endif


				title: '{{$value}}' ,data:'{{$key}}',foot: '{{$value}}' },
			@endforeach


        ]
    });
	@if (!empty($report["subtitles"]))
		/* carga los datos de la subcategoría en el data table */
			$('#dtreports tbody').on('click', 'td.details-control', function () {

				var tr = $(this).closest('tr');
				var row = table.row( tr );

				if ( row.child.isShown() ) {
					row.child.hide();
					tr.removeClass('shown');
				}
				else {
					row.child(subfamilyRows(row.data())).show();
					tr.addClass('shown');
					let table = row.child()[0].querySelector('table');
					$(table).dataTable({
						paging: false,
						ordering: true,
						info: false,
						searching: false,
						columns: [
							{  },
							@foreach ($report["subtitles"] as $key => $value)
								{
								@if(!empty($report["width"]) )
									width: '{{$report["width"][$key]}}',
								@endif
								 title: '{{$value}}', className:'{{$report["classes"][$key]}}' },
							@endforeach
						]
					});
				}
			})
	@endif

@endif


});

@if (!empty($report["subtitles"]))
	/* muestra los datos de la subcategoría en el data table */
	function subfamilyRows(data) {

		let html = '<table class="subtable table table-striped" style="width: 100%; ">';
		html += '<thead style="font-size: 11px"></thead>';
		html += '<tbody>';
			Object.keys(data.{{$report["subtable"]}}).forEach(function(key, index) {

			html += '<tr>'+
			`<td> </td>`+
				@foreach ($report["subtitles"] as $key => $value)
				`<td  class='{{$report["classes"][$key]}}'>${data.{{$report["subtable"]}}[key].{{$key}}}</td>`+
				@endforeach
			'</tr>';

		});
		html += '</tbody>';
		html += '</table>';
		return html;

	}

@endif

</script>
@stop
