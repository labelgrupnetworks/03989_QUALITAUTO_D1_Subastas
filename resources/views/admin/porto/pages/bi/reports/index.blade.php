@extends('admin::layouts.logged')
@section('content')

<section role="main" class="content-body">
	<section id="loader-page">
		<div class="lds-ripple"><div></div><div></div></div>
	</section>

	@include('admin::includes.header_content')
	@include('admin::pages.bi.reports.filters')
	@csrf

	<div class="row well header-well d-flex align-items-center">
		<div class="col-xs-12">
			<h1>{{ trans("admin-app.title.bi_reports") }}: {{$report["name"]}}</h1>
		</div>
	</div>
	<div class="row well">
		<div class="col-xs-12">
			<div class="row row-reports">
				<div class="col-xs-1 menu-reports" >
					<center><h3>{{ trans("admin-app.reportsBi.menuTitle") }}</h3></center>
					<ul >
						<li><a href="/admin/bi/report/typeBids"><i class="fa fa-bar-chart" aria-hidden="true"></i> {{ trans("admin-app.reportsBi.reports.type_bids") }}  </a></li>
						<li><a href="/admin/bi/report/salesSecs"><i class="fa fa-bar-chart" aria-hidden="true"></i> {{ trans("admin-app.reportsBi.reports.sale_category") }}  </a></li>

					</ul>
				</div>
				<div class="col-xs-11 body-reports">
				@foreach ($report["charts"] as $keyChart =>$chart)
					<div class="col-xs-6">
						@foreach ($chart->types as $type)
							<canvas id="report_{{$keyChart}}_{{$type}}" width="400" height="100%"></canvas>
						@endforeach
						{{-- <canvas id="report_{{$keyChart}}_doughnut" width="400" height="100"></canvas> --}}

					</div>
				@endforeach
			</div>
			</div>
		</div>
	</div>



</section>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
/*
const res = await fetch('bi/allcategories', {
	method: 'POST',
	body: new FormData(biReload)
});

let resJson = await res.json();
*/
/*
const getCategoryAwardsInfo = async () => {

const res = await fetch('bi/allcategories', {
	method: 'POST',
	body: new FormData(biReload)
});

let resJson = await res.json();
return resJson;
}
*/

@foreach ($report["charts"] as $keyChart =>$chart)
	@foreach ($chart->types as $type)
		const ctx_{{$keyChart}}_{{$type}} = document.getElementById('report_{{$keyChart}}_{{$type}}');

		new Chart(ctx_{{$keyChart}}_{{$type}} , {
		type: '{{$type}}',
		data: {
			labels: ['{!! implode("','",$chart->labels) !!}'],
			datasets: [
			@foreach($chart->datasets as $dataset)
				{
					label: '{{$dataset["title"]}}',
					data: [{{implode(",",$dataset["data"])}} ],
					borderWidth: 1
				},
			@endforeach

		]
		},
		options: {
			scales: {
			y: {
				beginAtZero: true
			}
			}
		}
		});
	@endforeach

@endforeach
/*
const ctx_{{$keyChart}} = document.getElementById('report_{{$keyChart}}');

	new Chart(ctx_{{$keyChart}} , {
	type: 'bar',
	data: {
		labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
		datasets: [{
		label: '# of Votes',
		data: [12, 19, 3, 5, 2, 3],
		borderWidth: 1
		}]
	},
	options: {
		scales: {
		y: {
			beginAtZero: true
		}
		}
	}
	});
*/
</script>
@stop
