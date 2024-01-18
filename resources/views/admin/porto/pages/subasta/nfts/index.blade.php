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
			<h1>{{ trans("admin-app.title.nfts") }}</h1>
		</div>
	</div>

	<div class="row well" style="position:relative;">

		<div class="loader-block">
			{{ trans("admin-app.information.charging") }}
		</div>

		<div class="col-xs-12 text-right mb-1 pt-1 pb-1" style="background-color: #ffe7e7">

			{{-- configuracion de tabla --}}
			@include('admin::includes.config_table_v2', ['id' => 'nfts_table', 'params' => $tableParams, 'formulario' => []])
		</div>


		<div class="col-xs-12 table-responsive">
			<table id="nfts_table" class="table table-striped table-condensed table-responsive" style="width:100%" data-order-name="order">
				<thead>
					<tr>
						<th></th>

						@foreach ($tableParams as $field => $display)
							<th class="{{$field}}">{{ trans("admin-app.fields.$field") }}</th>
						@endforeach

						<th><span>{{ trans("admin-app.vottun.fields.state_minted") }}</span></th>
						<th><span>{{ trans("admin-app.vottun.fields.mint") }}</span></th>
						<th><span>{{ trans("admin-app.vottun.fields.transfier") }}</span></th>

					</tr>
				</thead>
				<tbody>

					@forelse ($nfts as $nft)

					<tr data-state="{{ $nft->mint_state }}" data-num="{{ $nft->numhces_asigl0 }}" data-lin="{{ $nft->linhces_asigl0 }}" @if(!$nft->has_all_wallets) class="warning" @endif>

						<td>
							@if(!$nft->has_all_wallets)
								<i class="fa fa-exclamation-circle text-danger" data-toggle="tooltip" data-placement="top"
								title="{{ trans("admin-app.vottun.info.wallet_obligatory") }}" aria-hidden="true"></i>
							@endif
						</td>

						@foreach ($tableParams as $field => $display)
							<td class="{{$field}}" @if(!$tableParams[$field]) style="display: none" @endif>{{ $nft->{$field} }}</td>
						@endforeach

						<td>
							@if($nft->mint_state == 'minted')
							<span class="text-success">NFT minted</span>
							@elseif ($nft->mint_state == 'minting')
							<span class="mint-result">Comprobando estado</span>
							@else
							<span class="mint-result"></span>
							@endif
						</td>

						<td>
							@if(!$nft->has_all_wallets)
							<button class="btn btn-dark btn-sm" data-toggle="tooltip" data-placement="top"
								title="{{ trans("admin-app.vottun.info.wallet_obligatory") }}">
								<i class="fa fa-cloud-upload" aria-hidden="true"></i>
							</button>
							@elseif($nft->mint_state == 'notminted')
							<button class="btn btn-success btn-sm js-mint-button">
								<i class="fa fa-cloud-upload" aria-hidden="true"></i>
							</button>
							@elseif ($nft->mint_state == 'minting')
							<button class="btn btn-success btn-sm js-mint-button hidden">
								<i class="fa fa-cloud-upload" aria-hidden="true"></i>
							</button>
							@endif
						</td>

						<td>
							@if(!$nft->has_all_wallets)
							<button class="btn btn-dark btn-sm disabled" data-toggle="tooltip" data-placement="top"
								title="{{ trans("admin-app.vottun.info.wallet_obligatory") }}">
								<i class="fa fa-exchange" aria-hidden="true"></i>
							</button>
							@elseif($nft->transfer_id_nft)
							<span class="text-success">{{ trans("admin-app.vottun.info.nft_transfer") }}</span>

							@elseif($nft->mint_state == 'minted')
							<button class="btn btn-success btn-sm js-transfer-button">
								<i class="fa fa-exchange" aria-hidden="true"></i>
							</button>
							<span class="transfer-result hidden"></span>

							@else
							<button class="btn btn-success btn-sm js-transfer-button hidden"><i class="fa fa-exchange" aria-hidden="true"></i></button>
							<span class="transfer-result hidden">{{ trans("admin-app.vottun.info.nft_transfer") }}</span>
							@endif
						</td>
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
		{{ $nfts->links() }}
	</div>

</section>
@stop
