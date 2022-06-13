@php
	$subastaInst = new App\Models\Subasta();
	$videos = [];
	$videos = $subastaInst->getLoteVideos($item);
@endphp


<div class="{{ $class_square }} large_square">
    {{-- he puesto aquí los iconos por que el video tiene enlace y si lo meto dentro del otro enlace el html genera un momton de elementos a href abriendolos y cerrandolos --}}
    @if (!empty($item->contextra_hces1))
        <div style="position:absolute;left: 520px; top:2px;z-index: 100;">
            <a href="{{ $item->contextra_hces1 }}" target="_blank">
                <img src="/default/img/icons/video.png" style="height:16px" />
            </a>
        </div>
    @endif
    @if (Config::get('app.icon_multiple_images') && !empty($item->imagenes) && count($item->imagenes) > 1)
        <a title="{{ $titulo }}" <?= $url ?> style='text-decoration: none;'>
            <div style="position:absolute;left: 552px; top:2px;z-index: 100;">
                <img src="/default/img/icons/camara.png" style="height:20px" />
            </div>
            <div style="position:absolute;left: 573px; top:-2px;z-index: 100;">
                <img src="/default/img/icons/mas.png" style="height:8px" />
            </div>
        </a>
    @endif


    <div class="container-lot-large">

        <a title="{{ $titulo }}" <?= $url ?> style='text-decoration: none;'>
            <div class="image-lot-large col-xs-12 col-sm-4">

                @if ($item->retirado_asigl0 != 'N')
                    <div class="retired ">
                        {{ trans(\Config::get('app.theme') . '-app.lot.retired') }}
                    </div>
                @elseif($item->fac_hces1 == 'D' || $item->fac_hces1 == 'R')
                    <div class="retired" style="background:#2b373a;text-transform: lowercase; top:5px; right:5px">
                        {{ trans(\Config::get('app.theme') . '-app.subastas.dont_available') }}
                    </div>
                @elseif(\Config::get('app.awarded') && $item->cerrado_asigl0 == 'S' && (!empty($precio_venta) || $item->desadju_asigl0 == 'S' || ($item->subc_sub == 'H' && !empty($item->impadj_asigl0))))
                    <div class="retired" style="background:#2b373a;text-transform: lowercase;">
                        {{ trans(\Config::get('app.theme') . '-app.subastas.buy') }}
                    </div>
                @endif
                <div class="img_lot">
                    <img class="img-responsive lazy"
                        data-src="{{ Tools::url_img('lote_large', $item->num_hces1, $item->lin_hces1) }}"
                        alt="{{ $titulo }}">
                </div>
            </div>
        </a>
        <div class="col-xs-12 col-sm-8">
            <div class="lot-desc">
                <h3 class="lot-title">
                    {{ trans(\Config::get('app.theme') . '-app.lot.lot-name') }}
                    {{ $item->ref_asigl0 }}
                </h3>
                <div class="desc-lot-large">
                    @if ($item->retirado_asigl0 == 'N' || ($item->cerrado_asigl0 == 'S' && !empty($precio_venta)))

                        <?php /*<p>{{ $item->titulo_hces1 }}</p>*/ ?>
                        @if (\Config::get('app.descweb_hces1') || \Config::get('app.desc_hces1'))

                            @if (\Config::get('app.descweb_hces1'))
                                <?= $item->descweb_hces1 ?>
                            @elseif (\Config::get('app.desc_hces1'))
                                <?= $item->desc_hces1 ?>
                            @endif

                        @endif
                    @else
                        @if (\Config::get('app.descweb_hces1') || \Config::get('app.desc_hces1'))

                            @if (\Config::get('app.descweb_hces1'))
                                <?= $item->descweb_hces1 ?>
                            @elseif (\Config::get('app.desc_hces1'))
                                <?= $item->desc_hces1 ?>
                            @endif

                        @endif
                    @endif

                </div>
            </div>
            <?php //en historicono saldra precio de salida por lo que mejor lo ponemos así?>
            @if ($item->subc_sub != 'H' || !empty($precio_venta) || ($item->subc_sub == 'H' && !empty($item->impadj_asigl0)))
                <div class="salida-estima">
                    <span class="top-title">
                        @if ($item->tipo_sub != 'V')
                            @if (\Config::get('app.estimacion'))
                                {{ trans(\Config::get('app.theme') . '-app.lot.estimate') }}:
                                <?php //en historico n odeben salir preic osañlida
                                ?>
                            @elseif(\Config::get('app.impsalhces_asigl0'))
                                {{ trans(\Config::get('app.theme') . '-app.lot.lot-price') }}:
                            @endif
                        @else
                            {{ trans(\Config::get('app.theme') . '-app.subastas.price_sale') }}:
                        @endif
					</span>
                    <span class="price">
						@if ($item->tipo_sub != 'V')
							@if (\Config::get('app.estimacion'))
								{{ $item->formatted_imptas_asigl0 }} -
								{{ $item->formatted_imptash_asigl0 }}
								{{ trans(\Config::get('app.theme') . '-app.subastas.euros') }}
							@elseif(\Config::get('app.impsalhces_asigl0'))
								{{ $precio_salida }}
								{{ trans(\Config::get('app.theme') . '-app.subastas.euros') }}
							@endif
						@else
							{{ $item->formatted_actual_bid }}
							{{ trans(\Config::get('app.theme') . '-app.subastas.euros') }}
						@endif
                    </span>
                </div>
                <div class="puja-actual">

					<span class="top-title">
                        @if (($item->tipo_sub == 'P' || $item->tipo_sub == 'O' || $item->subabierta_sub == 'P' || $item->subabierta_sub == 'O') && $item->cerrado_asigl0 == 'N')
                            {{ trans(\Config::get('app.theme') . '-app.lot.puja_actual') }}
                        @elseif($item->cerrado_asigl0 == 'S' && (!empty($precio_venta) || ($item->subc_sub == 'H' && !empty($item->impadj_asigl0))))
                            {{ trans(\Config::get('app.theme') . '-app.subastas.buy_to') }}
                        @endif
					</span>

                    <span class="price">
                        @if ($item->fac_hces1 == 'D' || $item->fac_hces1 == 'R')

                        @elseif(($item->tipo_sub == 'P' || $item->tipo_sub == 'O' || $item->subabierta_sub == 'P') && $item->cerrado_asigl0 == 'N' && !empty($item->max_puja))
                            <span class="{{ $winner }}">
                                {{ \Tools::moneyFormat($item->max_puja->imp_asigl1) }}
                                {{ trans(\Config::get('app.theme') . '-app.subastas.euros') }}</span>

                        @elseif ($item->tipo_sub == 'W' && $item->subabierta_sub == 'O' && $item->cerrado_asigl0 == 'N')
                            <span class="{{ $winner }}">
								@if ($item->open_price >= $item->impsalhces_asigl0)
                                    {{ \Tools::moneyFormat($item->open_price) }}
                                    {{ trans(\Config::get('app.theme') . '-app.subastas.euros') }}
                                @endif
							</span>


                        @elseif(($item->cerrado_asigl0 == 'S' && $item->remate_asigl0 == 'S' && !empty($precio_venta)) || ($item->subc_sub == 'H' && !empty($item->impadj_asigl0)))
                            @if ($item->subc_sub == 'H' && !empty($item->impadj_asigl0))
                                @php($precio_venta = $item->impadj_asigl0)
                            @endif

                            {{ \Tools::moneyFormat($precio_venta) }}
                            {{ trans(\Config::get('app.theme') . '-app.subastas.euros') }}

                        @elseif($item->cerrado_asigl0 == 'S' && (!empty($precio_venta) || $item->desadju_asigl0 == 'S'))
                            {{ trans(\Config::get('app.theme') . '-app.subastas.buy') }}

                        @endif
					</span>
                    {{-- <div class="price">
                        @if ($item->fac_hces1 == 'D' || $item->fac_hces1 == 'R')
                            <p>{{ trans(\Config::get('app.theme') . '-app.subastas.dont_available') }}</p>
                        @elseif(($item->tipo_sub == 'P' || $item->tipo_sub == 'O' || $item->subabierta_sub == 'P') && $item->cerrado_asigl0 == 'N' && !empty($item->max_puja))
                            <p class="{{ $winner }}">
                                {{ \Tools::moneyFormat($item->max_puja->imp_asigl1) }}
                                {{ trans(\Config::get('app.theme') . '-app.subastas.euros') }}</p>
                        @elseif ($item->tipo_sub == 'W' && $item->subabierta_sub == 'O' && $item->cerrado_asigl0 == 'N')
                            <p class="{{ $winner }}">
                                @if ($item->open_price >= $item->impsalhces_asigl0)
                                    {{ \Tools::moneyFormat($item->open_price) }}
                                    {{ trans(\Config::get('app.theme') . '-app.subastas.euros') }}
                                @endif

                            </p>
                        @elseif(!empty($data['sub_data']) && !empty($data['sub_data']->opcioncar_sub) && $data['sub_data']->opcioncar_sub == 'S' && $item->tipo_sub == 'W' && $item->cerrado_asigl0 == 'N' && $item->fac_hces1 == 'N' && strtotime('now') > strtotime($item->orders_start) && strtotime('now') < strtotime($item->orders_end))
                            <div class="input-group">
                                <input placeholder="" class="form-control" value="" type="text">
                                <div class="input-group-btn">
                                    <button data-from="modal" type="button" class="lotlist-orden btn btn-lg btn-custom"
                                        ref="{{ $item->ref_asigl0 }}">{{ trans(\Config::get('app.theme') . '-app.lot.pujar') }}</button>
                                </div>
                            </div>
                        @elseif($item->tipo_sub == 'W' && ($item->subc_sub == 'S' || $item->subc_sub == 'A') && $item->compra_asigl0 == 'S' && $item->cerrado_asigl0 == 'S' && empty($precio_venta) && $item->desadju_asigl0 == 'N')
                            <p>{{ strtoupper(trans(\Config::get('app.theme') . '-app.subastas.buy_lot')) }}</p>
                        @elseif($item->tipo_sub == 'V' && $item->cerrado_asigl0 == 'N')
                            <p>{{ strtoupper(trans(\Config::get('app.theme') . '-app.subastas.buy_lot')) }}</p>
                        @elseif($item->cerrado_asigl0 == 'D')
                            <p>{{ trans(\Config::get('app.theme') . '-app.subastas.dont_available') }}</p>
                        @elseif(($item->cerrado_asigl0 == 'S' && $item->remate_asigl0 == 'S' && !empty($precio_venta)) || ($item->subc_sub == 'H' && !empty($item->impadj_asigl0)))
                            @if ($item->subc_sub == 'H' && !empty($item->impadj_asigl0))
                                @php($precio_venta = $item->impadj_asigl0)
                            @endif
                            <p>{{ \Tools::moneyFormat($precio_venta) }}
                                {{ trans(\Config::get('app.theme') . '-app.subastas.euros') }}</p>
                        @elseif($item->cerrado_asigl0 == 'S' && (!empty($precio_venta) || $item->desadju_asigl0 == 'S'))
                            <p>{{ trans(\Config::get('app.theme') . '-app.subastas.buy') }}</p>
                        @elseif($item->cerrado_asigl0 == 'S' && empty($precio_venta))
                            <p>{{ trans(\Config::get('app.theme') . '-app.subastas.dont_buy') }}</p>
                        @endif
                    </div> --}}
                </div>
            @endif

			<div class="button-container">
				<div class="row">
					<div class="col-xs-12 col-md-6">
						<a title="{{ $titulo }}" <?= $url ?> class="btn btn-3">{{ trans("$theme-app.lot.see_details") }}</a>
					</div>

					<div class="col-xs-12 col-md-6">
					@if( \Config::get('app.awarded'))
                            <p class="salida">
                                @if($item->cerrado_asigl0 == 'D')

								{{-- devuelto, no debe aparecer boton --}}

                                @elseif($item->cerrado_asigl0 == 'S' && $item->remate_asigl0 =='S' &&  (!empty($precio_venta) ) || ($item->subc_sub == 'H' && !empty($item->impadj_asigl0)) )
									@if($item->subc_sub == 'H' && !empty($item->impadj_asigl0))
										@php($precio_venta = $item->impadj_asigl0)
									@endif
									{{-- vendido, no debe aparecer boton --}}

                                @elseif($item->cerrado_asigl0 == 'S' &&  (!empty($precio_venta) || $item->desadju_asigl0 =='S') )
								{{-- vendido, no debe aparecer boton --}}

                                @elseif($item->tipo_sub == 'W' && ($item->subc_sub == 'S' ||   $item->subc_sub == 'A')  && $item->compra_asigl0 == 'S' && $item->cerrado_asigl0 == 'S' && empty($precio_venta) )
								{{-- no vendido y con compra a S, debe aparecer boton --}}
								<a title="{{ $titulo }}" <?= $url ?> class="btn btn-2">{{ strtoupper(trans(\Config::get('app.theme') . '-app.subastas.buy_lot')) }}</a>

                                @elseif($item->cerrado_asigl0 == 'S' &&  empty($precio_venta) &&  $item->subc_sub != 'H')
								{{-- No vendido, no debe aparecer boton --}}

								@elseif($item->cerrado_asigl0=='N'  && $item->tipo_sub == 'W' &&  ($item->subc_sub == 'S' ||   $item->subc_sub == 'A'))
								{{-- No cerrado y en subasta, debe aparecer boton de pujar --}}
								<a title="{{ $titulo }}" <?= $url ?> class="btn btn-2">{{ trans("$theme-app.lot.pujar") }}</a>
								@endif
                            </p>
                    @endif
					</div>
				</div>
			</div>
			{{-- añado una linea más para las subastas W abiertas pujas, que se muestre el numero de licitadores y de pujas  --}}
			@if ($item->tipo_sub == 'W' &&  ($item->subc_sub == 'S' ||   $item->subc_sub == 'A') &&  ( $item->subabierta_sub == 'P' || $item->subabierta_sub == 'O'))
				<div class="bids-and-licits col-xs-12" style="text-align:right">
					@if (!empty($videos))
						<i class="fa fa-play" width="16px" height="16px" aria-hidden="true"></i>
					@endif
					<img src="/default/img/icons/hammer.png" style="margin-right: 5px; margin-left: 15px;" width="16px" height="16px">{{ $item->total_pujas }}
					<img src="/default/img/icons/licits.png" style="margin-left: 10px; margin-right: 5px;" width="16px" height="16px">{{ $item->total_postores }}
				</div>
			@endif
        </div>
    </div>



</div>
