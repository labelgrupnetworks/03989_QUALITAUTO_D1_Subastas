@extends('layouts.default')

@section('title')
{{ trans($theme.'-app.head.title_app') }}
@stop

@section('content')
<script>
	routing.node_url = '{{ Config::get("app.node_url") }}';
	var auctions_info = @JSON($data['values']);
	auctions_info.user = @JSON(\Session::get('user'));
	var rooms = [];
</script>

<script src="{{ URL::asset('vendor/tiempo-real/node_modules/socket.io/node_modules/socket.io-client/socket.io.js') }}"></script>
<script src="{{ Tools::urlAssetsCache('/themes/'.$theme.'/custom_node_panel.js') }}"></script>
<script src="{{ URL::asset('js/hmac-sha256.js') }}"></script>

@include('pages.panel.principal_bar')

<style>
	@media (max-width: 600px) {
		.custom-wrapper-responsive .auc-data-custom p:first-child {
			/*height: 25px;
			padding-right: 10px;*/
			font-size: 12px;
		}
	}
</style>

<section class="account">
	<div class="container">
		<div class="row">

				@php
                    $tab="orders";
                    if(!empty($data['favorites'])){
                        $tab="favorites";
                    }

                    use App\libs\Currency;
                    $currency = new Currency();
                    $divisa = !empty(Session::get('user.currency'))? Session::get('user.currency') : 'EUR';
                    $currency->setDivisa($divisa);
					$divisas = $currency->getAllCurrencies();
                @endphp

			<script>
				var currency =  @JSON($divisas);
				var divisa = @JSON($divisa);
			</script>

			<div class="col-xs-12">
				@include('pages.panel.menu')
			</div>

			<div class="col-xs-12">

				<div role="tabpanel" class="user-datas-title">
					@if(!empty($data['favorites']))
					<p>{{ trans($theme.'-app.user_panel.favorites') }}</p>
					@else
					<p>{{ trans($theme.'-app.user_panel.orders') }}</p>
					@endif
				</div>

				<div class="panel-group" id="accordion">
					<div class="panel panel-default marg-resp">
						@php

						$finalized = [];
						$notFinalized = [];
						@endphp


						<div class="title-collapse" data-toggle="collapse" data-target="#auctions_accordion">
							<p>
								{{ trans($theme.'-app.foot.auctions-active') }}
								<span style="float: right"><i class="fa fa-caret-right" aria-hidden="true"></i></span>
							</p>
						</div>

						{{--<div class="auctions-list-title"><strong>{{ trans($theme.'-app.subastas.next_auctions') }}</strong></div>--}}
						<div class="collapse js-title-collapse in" id="auctions_accordion">
						@foreach($data['values'] as $key_sub => $all_inf)

						@php

						//ver si la subasta está cerrada
						$SubastaTR = new \App\Models\SubastaTiempoReal();
						$SubastaTR->cod =$all_inf['inf']->cod_sub;
						$SubastaTR->session_reference = $all_inf['inf']->reference;

						$ended = $SubastaTR->getStatusSessions();
						$subasta_finalizada = false;

						if($ended && $all_inf['inf']->tipo_sub != 'V'){
							$subasta_finalizada = true;
							array_unshift($finalized, $all_inf);
						}
						else{
							array_unshift($notFinalized, $all_inf);
						}

						$escalado = new \App\Models\Subasta;
						$escalado->cod = $key_sub;
						@endphp


						@if ($all_inf['inf']->tipo_sub != 'V' && !$subasta_finalizada)
						<script>
							rooms.push('{{$key_sub}}');
						</script>

							@include('pages.panel.orders_auction', ['subasta_finalizada' => false])

						@endif

						@endforeach
						</div>

						@if(count($finalized) > 0)

						<div class="title-collapse mt-3" data-toggle="collapse" data-target="#auctions_fin_accordion">
							<p>
								{{ trans($theme.'-app.subastas.finished_auctions') }}
								<span style="float: right"><i class="fa fa-caret-right" aria-hidden="true"></i></span>
							</p>
						</div>

						<div class="collapse js-title-collapse" id="auctions_fin_accordion">
						@foreach ($finalized as $all_inf)
							@include('pages.panel.orders_auction', ['subasta_finalizada' => true])
						@endforeach
						</div>

						@endif
					</div>
				</div>
			</div>
		</div>
	</div>
</section>


<div id="modalPujarPanel" class="container modal-block mfp-hide ">
	<div data-to="pujarLotePanel" class="modal-sub-w">
			<section class="panel">
					<div class="panel-body">
							<div class="modal-wrapper">
									<div class=" text-center single_item_content_">
										<p class="class_h1">{{ trans($theme.'-app.lot.confirm_bid') }}</p><br/>
										<span for="bid" class='desc_auc'>{{ trans($theme.'-app.lot.you_are_bidding') }} </span> <strong><span class="precio"></span> €</strong><br/>
										<span class="ref_orden hidden"></span>
										<br>
											<button id="confirm_puja_panel" class="btn btn-color button_modal_confirm btn-custom">{{ trans($theme.'-app.lot.confirm') }}</button>
											<div class='mb-10'></div>
											 <div class='mb-10'></div>
											<ul class="items_list">
												<li><?=trans($theme.'-app.lot.tax_not_included')?> </li>

											</ul>
									</div>
							</div>
					</div>
			</section>
	</div>
</div>

<script>
	$( document ).ready(function() {
		$('.js-title-collapse').on('show.bs.collapse', function (e) {
			$(`[data-target^='#${e.target.id}'] i.fa.fa-caret-right`).removeClass('fa-caret-right').addClass('fa-caret-down');
		});

		$('.js-title-collapse').on('hide.bs.collapse', function (e) {
			$(`[data-target^='#${e.target.id}'] i.fa.fa-caret-down`).removeClass('fa-caret-down').addClass('fa-caret-right');
		});
	});

</script>
@stop
