@extends('admin::layouts.logged')
@section('content')

<section role="main" class="content-body">
	<section id="loader-page">
		<div class="lds-ripple"><div></div><div></div></div>
	</section>

	@include('admin::includes.header_content')
	@include('admin::pages.bi._sidebar-right-bi')
	@csrf

	<div class="row well header-well d-flex align-items-center">
		<div class="col-xs-12">
			<h1>{{ trans("admin-app.title.bi_long") }}</h1>
		</div>
	</div>

	<div class="row well">

		<div class="col-xs-12">
			<div class="row">
				<div class="col-xs-4">
					@include('admin::components.info_box', [
						'id' => 'box-auction',
						'extraClass' => 'icon-invert',
						'iconBg' => 'bg-info',
						'iconImg' => '/themes_admin/porto/assets/images/icons/auction_60_icon.png',
						'title' => trans("admin-app.fields.auctions"),
						'subTitle' => '',
						'value' => ''])
				</div>

				<div class="col-xs-4">
					@include('admin::components.info_box', [
						'id' => 'box-lots',
						'extraClass' => 'icon-invert',
						'iconBg' => 'bg-warning',
						'iconImg' => '/themes_admin/porto/assets/images/icons/bustsimple_96_icon.png',
						'title' => trans("admin-app.title.lots"),
						'subTitle' => trans("admin-app.bi.sold"),
						'value' => trans("admin-app.bi.awarded")
						])
				</div>

				<div class="col-xs-4">
					@include('admin::components.info_box', [
						'id' => 'box-licits',
						'extraClass' => 'icon-invert',
						'iconBg' => 'bg-success',
						'iconImg' => '/themes_admin/porto/assets/images/icons/users_60_icon.png',
						'title' => trans("admin-app.bi.bidders"),
						'subTitle' => '',//'Pujas ' . $infoSales['pujadores'],
						'value' => ''
						])
				</div>

			</div>
		</div>

		<div class="col-xs-12 mb-3">
			<h1>{{ trans("admin-app.title.awards") }}</h1>
			<canvas id="adjudicacionesChart" width="400" height="100"></canvas>
		</div>

		<div class="col-xs-12 mb-3">
			<div class="row">

				<div class="col-xs-4">
					<canvas id="auctionForMonthChart" width="400" height="100"></canvas>
				</div>

				<div class="col-xs-4">
					<canvas id="awardsValuesForMonthChart" width="400" height="100"></canvas>
				</div>

				<div class="col-xs-4">
					<canvas id="awardsForSectionChart" width="400" height="100"></canvas>
				</div>

			</div>
		</div>

		<div class="col-xs-12 mb-3">
			<table id="example" class="table table-striped display" style="width:100%">
				<thead>
					<tr>
						<th></th>
						<th>{{ trans("admin-app.bi.family") }}</th>
						<th>{{ trans("admin-app.bi.num_lots") }}</th>
						<th>{{ trans("admin-app.bi.starting_price") }}</th>
						<th>{{ trans("admin-app.bi.lots_awarded") }}</th>
						<th>{{ trans("admin-app.bi.award_value") }}</th>
						<th>{{ trans("admin-app.bi.revaluation") }}</th>
						<th>{{ trans("admin-app.bi.increase") }}</th>
						<th>{{ trans("admin-app.bi.weighing") }}</th>
						<th>{{ trans("admin-app.bi.weighing_value") }}</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th></th>
						<th>{{ trans("admin-app.bi.total") }}</th>
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

	@include('admin::pages.bi._modal')

</section>

<input type="hidden" name="auctions_ajax" value='1'>

{{-- <input type="hidden" name="clientesForDate" value='@json($clientesForDate)'> --}}

@stop
