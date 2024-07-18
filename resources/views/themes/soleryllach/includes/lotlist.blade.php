@php
	$subastaInst = new App\Models\Subasta();
	$videos = $subastaInst->getLoteVideos($item);
@endphp


<div class="{{$class_square}} square">
	{{--  he puesto aquí los iconos por que el video tiene enlace y si lo meto dentro del otro enlace el html genera un momton de elementos a href abriendolos y cerrandolos--}}
	@if(!empty($item->contextra_hces1))
	<div class="video-wrapper">
		<a href="{{$item->contextra_hces1}}" target="_blank">
			<img src="/default/img/icons/video.png"/>
		</a>
	</div>
	@endif

    <a title="{{ $titulo }}"  <?= $url;?> style='text-decoration: none;'>

        <div class="item_lot" data-with-rarity="{{ !empty($rarity) ? "true" : "false" }}">
			<div class="border_img_lot">
				<div class="img_lot">
					<img class="img-responsive lazy" data-src="{{Tools::url_img('lote_medium', $item->num_hces1, $item->lin_hces1, null, true)}}" alt="{{$titulo}}">

				</div>

				@if( $item->retirado_asigl0 !='N')
					<div class="retired-border">
						<div class="retired">
							<span class="retired-text lang-{{ \Config::get('app.locale') }}">
								{{ trans(\Config::get('app.theme').'-app.lot.retired') }}
							</span>
						</div>
					</div>
				@elseif($item->fac_hces1 == 'D' || $item->fac_hces1 == 'R')
					<div class="retired-border">
						<div class="retired">
							<span class="dont_available-text lang-{{ \Config::get('app.locale') }}">
								{{ trans(\Config::get('app.theme').'-app.subastas.dont_available') }}
							</span>
						</div>
					</div>
					@elseif(Config::get('app.awarded') && $item->cerrado_asigl0 == 'S' &&  (!empty($precio_venta) || $item->desadju_asigl0 =='S' || $item->subc_sub == 'H' && !empty($item->impadj_asigl0)) )
					<div class="retired-border">
						<div class="retired selled">
							<span class="retired-text lang-{{ \Config::get('app.locale') }}">
								{{ trans(\Config::get('app.theme').'-app.subastas.buy') }}
							</span>
						</div>
					</div>

				@elseif(Session::has('user') && $item->cerrado_asigl0 == 'N')
					<button class="btn btn-fav-grid js-btn-fav-grid" data-is-favorite="{{ $isFavorite ? 'true' : 'false' }}"
						data-ref-asigl0="{{ $item->ref_asigl0 }}" data-cod-sub="{{ $item->cod_sub }}">
							<i class="fa fa-star @if(!$isFavorite) fa-star-o @endif"></i>
					</button>
				@endif

			</div>

			<p class="max-line-1 rarity-block @if($rarity) rarity-show @endif">{{ $rarity ?? '-' }}</p>

            <div class="data-container">

                @if(!empty($titulo))
                    <div class="title_lot square-lot">
                        <h4 class="mr-auto">{{ $titulo }}
							@if ($item->isItp)
								<span class="lot-itp-mark">*</span>
							@endif
						</h4>

						@if(Config::get('app.icon_multiple_images') && !empty($item->imagenes) && count($item->imagenes) > 1)
						<div class="camera-wrapper">
							<div class="camera-icon">
								<img src="/default/img/icons/camara.png"/>
							</div>
							<div class="plus-icon">
								<img src="/default/img/icons/mas.png"/>
							</div>
						</div>
						@endif

						@if (!empty($videos))
							<i class="fa fa-play" style="margin-right: 10px;" width="19" height="19" aria-hidden="true"></i>
						@endif

                    </div>
                @endif

                @if( ( \Config::get( 'app.descweb_hces1' ) ) ||  ( \Config::get( 'app.desc_hces1' )))
                    <div class="desc_lot">
                            @if( \Config::get('app.descweb_hces1'))
                                <?= $item->descweb_hces1 ?>
                            @elseif ( \Config::get('app.desc_hces1' ))
                                <?= $item->desc_hces1 ?>
                            @endif
                    </div>
                @endif
                <div class="data-price">
                    @if($item->retirado_asigl0 == 'N')

						<p class="salida" data-position="price-sale">
                        @if($item->tipo_sub != 'V')

								@if(\Config::get('app.estimacion'))
								{{ trans(\Config::get('app.theme').'-app.lot.estimate') }} <span> {{$item->formatted_imptas_asigl0}} -  {{$item->formatted_imptash_asigl0}} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</span>

								@elseif(\Config::get('app.impsalhces_asigl0') && ($item->subc_sub !='H'  || !empty($precio_venta)))
									@if($precio_salida ==0)
										{{ trans(\Config::get('app.theme').'-app.lot.free') }}
									@else
										<span style="visibility: {{ $item->ocultarps_asigl0 != 'S' ? 'visible' : 'hidden'}}">{{ trans(\Config::get('app.theme').'-app.lot.lot-price') }}  <span> {{$precio_salida}} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</span></span>
									@endif
								@endif

                        @elseif($item->subc_sub !='H')
                            {{ trans(\Config::get('app.theme').'-app.subastas.price_sale') }} <span>{{$precio_salida}}  {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</span>
                        @endif
						</p>

						<div class="d-flex">
							<p class="salida" data-position="actual-bid">
							@if($item->subc_sub !='H' && $item->cerrado_asigl0 == 'N')

								@if(($item->tipo_sub == 'P' || $item->tipo_sub== 'O' || $item->subabierta_sub == 'P') && $item->cerrado_asigl0 == 'N' && !empty($item->max_puja))
									{{ trans(\Config::get('app.theme').'-app.lot.puja_actual') }}<span class="{{$winner}}">  {{ \Tools::moneyFormat($item->max_puja->imp_asigl1) }} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</span>

								@elseif ($item->tipo_sub == 'W' && $item->subabierta_sub == 'O' && $item->cerrado_asigl0 == 'N' && $item->open_price >= $item->impsalhces_asigl0  && $item->open_price > 0 )
									{{ trans(\Config::get('app.theme').'-app.lot.puja_actual') }} <span class="{{$winner}}">  {{ \Tools::moneyFormat($item->open_price) }} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</span>
								@endif

							@elseif(Config::get('app.awarded'))
								@if($item->cerrado_asigl0 == 'D')
									<span class="text-uppercase">{{ trans(\Config::get('app.theme').'-app.subastas.dont_available') }}</span>
								@elseif($item->cerrado_asigl0 == 'S' && $item->remate_asigl0 =='S' &&  (!empty($precio_venta) ) || ($item->subc_sub == 'H' && !empty($item->impadj_asigl0)) )
									@if($item->subc_sub == 'H' && !empty($item->impadj_asigl0))
										@php($precio_venta = $item->impadj_asigl0)
									@endif
									{{ trans(\Config::get('app.theme').'-app.subastas.buy_to') }} <span >{{ \Tools::moneyFormat($precio_venta) }} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</span>
								@elseif($item->cerrado_asigl0 == 'S' &&  (!empty($precio_venta) || $item->desadju_asigl0 =='S') )
									<span class="text-uppercase">{{ trans(\Config::get('app.theme').'-app.subastas.buy') }}</span>
								@elseif($item->tipo_sub == 'W' && ($item->subc_sub == 'S' ||   $item->subc_sub == 'A')  && $item->compra_asigl0 == 'S' && $item->cerrado_asigl0 == 'S' && empty($precio_venta) )
									<span class="text-uppercase color-primary">{{ trans(\Config::get('app.theme').'-app.subastas.buy_lot') }}</span>
								@elseif($item->cerrado_asigl0 == 'S' &&  empty($precio_venta) &&  $item->subc_sub != 'H')
									<span class="text-uppercase">{{ trans(\Config::get('app.theme').'-app.subastas.dont_buy') }}</span>
								@elseif($item->cerrado_asigl0=='N'  && $item->tipo_sub == 'W' &&  ($item->subc_sub == 'S' ||   $item->subc_sub == 'A') && $item->subabierta_sub == 'P')
									<span class="text-uppercase">{{ trans(\Config::get('app.theme').'-app.lot.pujar') }}</span>
								@endif

							@endif
							</p>

							{{-- añado una linea más para las subastas W abiertas pujas, que se muestre el numero de licitadores y de pujas  --}}
							<p class="salida ml-auto" data-position="number-bids">
								@if ($item->tipo_sub == 'W' &&  ($item->subc_sub == 'S' ||   $item->subc_sub == 'A') &&  ( $item->subabierta_sub == 'P' || $item->subabierta_sub == 'O'))
									<img src="/default/img/icons/hammer.png" style="margin-right: 5px;" width="19" height="19">{{ $item->total_pujas }}
									<img src="/default/img/icons/licits.png" style="margin-left: 10px; margin-right: 5px;" width="19" height="19">{{ $item->total_postores }}
								@endif
							</p>
						</div>

                    @else

						<p class="salida" data-position="price-sale"></p>

						<div class="d-flex">
							<p class="salida" data-position="actual-bid"></p>
							<p class="salida ml-auto" data-position="number-bids"></p>
						</div>

					@endif

                </div>

            </div>

        </div>
    </a>
</div>
