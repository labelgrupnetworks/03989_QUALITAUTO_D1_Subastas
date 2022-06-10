<div class="col-xs-12 text-right mb-1 pt-1 pb-1" style="background-color: #ffe7e7">
		<a class="btn btn-success btn-sm" href="{{route('winners.export',$idauction)}}">{{ trans('admin-app.button.download_excel') }}</a>
		@include('admin::includes.config_table', ['id' => 'tablePujas', 'params' => ((array) $filter)])

</div>
<table id="tablePujas" class="table table-striped table-condensed table-responsive" style="width:100%" data-order-name="order_winner">
	<thead>
		<tr>
			<th class="ref_csub" style="cursor: pointer" data-order="ref_csub">
				{{trans('admin-app.fields.reflot')}}
				   @if(request()->order_winner == 'ref_csub')
					   <span style="margin-left: 5px; float: right;">
						   @if(request()->order_winner_dir == 'asc')
								<i class="fa fa-arrow-up" style="color:green" aria-hidden="true"></i>
							   @else
							<i class="fa fa-arrow-down" style="color:red" aria-hidden="true"></i>
						   @endif
					   </span>
				   @endif
			   </th>
			   <th class="descweb_hces1">
				   {{trans('admin-app.fields.lot.desc_hces1')}}
				</th>
			   <th class="cod2_cli" style="cursor: pointer" data-order="cod2_cli">
				{{trans('admin-app.fields.cod2_cli')}}
				   @if(request()->order_winner == 'cod2_cli')
					   <span style="margin-left: 5px; float: right;">
						   @if(request()->order_winner_dir == 'asc')
								<i class="fa fa-arrow-up" style="color:green" aria-hidden="true"></i>
							   @else
							<i class="fa fa-arrow-down" style="color:red" aria-hidden="true"></i>
						   @endif
					   </span>
				   @endif
			   </th>
			   <th class="clifac_csub" style="cursor: pointer" data-order="clifac_csub">
				{{trans('admin-app.fields.cod_sub')}}
				   @if(request()->order_winner == 'clifac_csub')
					   <span style="margin-left: 5px; float: right;">
						   @if(request()->order_winner_dir == 'asc')
								<i class="fa fa-arrow-up" style="color:green" aria-hidden="true"></i>
							   @else
							<i class="fa fa-arrow-down" style="color:red" aria-hidden="true"></i>
						   @endif
					   </span>
				   @endif
			   </th>
			   <th class="rsoc_cli" style="cursor: pointer" data-order="rsoc_cli">
				{{trans('admin-app.fields.rsoc_cli')}}
				   @if(request()->order_winner == 'rsoc_cli')
					   <span style="margin-left: 5px; float: right;">
						   @if(request()->order_winner_dir == 'asc')
								<i class="fa fa-arrow-up" style="color:green" aria-hidden="true"></i>
							   @else
							<i class="fa fa-arrow-down" style="color:red" aria-hidden="true"></i>
						   @endif
					   </span>
				   @endif
			   </th>
			   <th class="licit_csub" style="cursor: pointer" data-order="licit_csub">
				{{trans('admin-app.fields.licit_csub')}}
				   @if(request()->order_winner == 'licit_csub')
					   <span style="margin-left: 5px; float: right;">
						   @if(request()->order_winner_dir == 'asc')
								<i class="fa fa-arrow-up" style="color:green" aria-hidden="true"></i>
							   @else
							<i class="fa fa-arrow-down" style="color:red" aria-hidden="true"></i>
						   @endif
					   </span>
				   @endif
			   </th>
			   <th class="fec_asigl1" style="cursor: pointer" data-order="fec_asigl1">
				{{trans('admin-app.fields.fecfra_csub')}}
				   @if(request()->order_winner == 'fec_asigl1')
					   <span style="margin-left: 5px; float: right;">
						   @if(request()->order_winner_dir == 'asc')
								<i class="fa fa-arrow-up" style="color:green" aria-hidden="true"></i>
							   @else
							<i class="fa fa-arrow-down" style="color:red" aria-hidden="true"></i>
						   @endif
					   </span>
				   @endif
			   </th>
			   <th class="himp_csub" style="cursor: pointer" data-order="himp_csub">
				{{trans('admin-app.fields.himp_orlic')}}
				   @if(request()->order_winner == 'himp_csub')
					   <span style="margin-left: 5px; float: right;">
						   @if(request()->order_winner_dir == 'asc')
								<i class="fa fa-arrow-up" style="color:green" aria-hidden="true"></i>
							   @else
							<i class="fa fa-arrow-down" style="color:red" aria-hidden="true"></i>
						   @endif
					   </span>
				   @endif
			   </th>

			<th>Acciones</th>
		</tr>
	</thead>
	<tbody>
		<tr id="filters">
			<form class="form-group" action="">
				<input type="hidden" name="order_orders" value="{{ request('order_orders', 'cod_cli') }}">
				<input type="hidden" name="order_winner_dir" value="{{ request('order_winner_dir', 'desc') }}">
				@foreach($filter as $index => $item)
					<td class="{{$index}}">{!! $item !!}</td>
				@endforeach
				<td class="d-flex">
					<input type="submit" class="btn btn-info w-100"
						value="{{ trans("admin-app.button.search") }}">
						<a
						href="{{ route( request()->route()->getName(), ['subasta' => $idauction, 'menu' => 'subastas'])}}"

						class="btn btn-warning w-100">{{ trans("admin-app.button.restart") }}</a>
				</td>
			</form>
		</tr>
		@foreach($ganadores as $k => $item)
		<tr id="ganador---{{$item->ref_csub}}">
			<td class="ref_csub">{!! $item->ref_csub !!}</td>
			<td> {!! $item->descweb_hces1 !!}


			<td class="cod2_cli">{{ $item->cod2_cli }}</td>

			<td class="clifac_csub">{{ $item->clifac_csub }}</td>


			<td class="rsoc_cli">{!! $item->rsoc_cli !!} </td>
			<td class="licit_csub">{{$item->licit_csub}} </td>
			<td class="fec_asigl1">{!! \Tools::getDateFormat($item->fec_asigl1, 'Y-m-d H:i:s', 'd/m/Y H:i:s') !!}</td>
			<td class="himp_csub">{!! \Tools::moneyFormat($item->himp_csub,'€', 2) !!}</td>
			<td></td>
		</tr>
		@endforeach
	</tbody>
</table>

{{-- <script>
	$(document).on('ready', function () {

		$('thead th').on('click', function(e){

			if(typeof this.classList[0] == 'undefined' || this.classList[0] == 'descweb_hces1'){
				return;
				}

			let url = new URL(window.location.href);
			url.searchParams.set('order_winner', this.classList[0]);
			url.searchParams.set('order_dir', 'asc');

			if(this.querySelector('i.fa-arrow-up') !== null){
				url.searchParams.set('order_dir', 'desc');
			}
			window.location.href = url;
		});

	});

</script>
 --}}
