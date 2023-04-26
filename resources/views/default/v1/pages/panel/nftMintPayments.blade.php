{{-- Si hay alguno pendiente de transferir no se dejara pagarle resto, para que se paguen todos juntos --}}
@if(count($pendingPay) > 0 && count($noTransfer) == 0 )

	<div class="account-user color-letter  pendientes_pago mb-2">

			<div class="row">
				<h2>{!! trans(\Config::get('app.theme').'-app.user_panel.title_pending_mint_pays_NFT') !!}</h2>

				@php

					$total = 0;
					$mintsIds = array();


				@endphp
				@foreach($pendingPay as $pending)
					@php

					$total+=$pending->cost_mint_nft;
					$mintsIds[]=$pending->mint_id_nft;

					@endphp
					<div class="col-xs-12  ">
						<div class="col-xs-12 col-md-9 payment-text-cell"> <strong> {{ trans(\Config::get('app.theme').'-app.user_panel.lot') }}:</strong>  {{$pending->descweb_hces1}} </div>
						<div class="col-xs-12 col-md-3 payment-money-cell">{{\Tools::moneyFormat($pending->cost_mint_nft,"€",2)}}</div>
					</div>


				@endforeach
				<div class="col-xs-12 ">
					<div class="col-xs-12 col-md-9  payment-total-cell">{{ trans(\Config::get('app.theme').'-app.user_panel.total_pay') }}:</div>
					<div class="col-xs-12 col-md-3 payment-money-cell">{{\Tools::moneyFormat($total,"€",2)}}</div>
				</div>


				<div class="col-xs-12 mt-4 mb-4 text-right">
					<a href="{{ Route("mintPayUrl", ["operationId" => implode("_",$mintsIds)])}}" class="button-principal"> {!! trans(\Config::get('app.theme').'-app.user_panel.pay') !!} </a>


				</div>

			</div>


	</div>

@endif
