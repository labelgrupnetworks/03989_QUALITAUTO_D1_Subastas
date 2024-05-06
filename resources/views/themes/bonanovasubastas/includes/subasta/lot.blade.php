<div class="square">
    <div class="item_lot content_item_mini" data-large-src="{{Tools::url_img('lote_large', $item->num_hces1, $item->lin_hces1)}}">
        <div class="border_img_lot">
            <a title="{{ $titulo }}" <?= $url ?>>
                <div class="img_lot">
                    <img class="img-responsive"
                        src="{{ Tools::url_img('lote_medium', $item->num_hces1, $item->lin_hces1) }}"
                        alt="{{ $titulo }}" loading="lazy">
                </div>
                @if ($item->retirado_asigl0 != 'N')
                    <div class="retired ">
                        {{ trans($theme . '-app.lot.retired') }}
                    </div>
                @elseif($item->fac_hces1 == 'D' || $item->fac_hces1 == 'R')
                    <div class="retired" style ="text-transform: lowercase;">
                        {{ trans($theme . '-app.subastas.dont_available') }}
                    </div>
                @elseif(
                    \Config::get('app.awarded') &&
                        $item->cerrado_asigl0 == 'S' &&
                        (!empty($precio_venta) ||
                            $item->desadju_asigl0 == 'S' ||
                            ($item->subc_sub == 'H' && !empty($item->impadj_asigl0))))
                    <div class="retired" style ="background:#2b373a;">
                        {{ trans($theme . '-app.subastas.buy') }} 
                    </div>
                @endif
            </a>
        </div>
        <div class="data-container">

			<div class="title_ref">
				<a title="{!! $titulo !!}" {!! $url !!}>
					{{ $item->ref_asigl0 }}
				</a>
			</div>

            @if (!empty($titulo))
                <div class="title_lot">
                    <h4>
                        <a title="{!! $titulo !!}" {!! $url !!}>
							{!! $titulo !!}
                        </a>
                    </h4>
                </div>
            @endif
            @if (\Config::get('app.descweb_hces1') || \Config::get('app.desc_hces1'))
                <div class="desc_lot">
                    @if (\Config::get('app.descweb_hces1'))
                        <?= $item->descweb_hces1 ?>
                    @elseif (\Config::get('app.desc_hces1'))
                        <?= $item->desc_hces1 ?>
                    @endif
                </div>
            @endif
            <div class="data-price">
                @if ($item->retirado_asigl0 == 'N' && $item->fac_hces1 != 'D' && $item->fac_hces1 != 'R')

                    @if ($item->tipo_sub != 'V')

                        @if (\Config::get('app.estimacion'))
                            <p class="salida">{{ trans($theme . '-app.lot.estimate') }} <span>
                                    {{ $item->formatted_imptas_asigl0 }} - {{ $item->formatted_imptash_asigl0 }}
                                    {{ trans($theme . '-app.subastas.euros') }}</span></p>
                        @elseif(\Config::get('app.impsalhces_asigl0'))
                            <p class="salida salida_price"
                                style="visibility: {{ $item->ocultarps_asigl0 != 'S' ? 'visible' : 'hidden' }}">
								<span class="price_field">
                                	{{ trans($theme . '-app.lot.lot-price') }}
								</span>
								<span class="price_value">
                                    {{ $precio_salida }}
                                    {{ trans($theme . '-app.subastas.euros') }}
								</span>
							</p>
                        @endif
                    @else
                        <p class="salida">{{ trans($theme . '-app.subastas.price_sale') }}
                            <span>
                                {{ $item->formatted_actual_bid }}
                                {{ trans($theme . '-app.subastas.euros') }}
                            </span>
                        </p>
                    @endif

                    @if (($item->tipo_sub == 'P' || $item->tipo_sub == 'O') && $item->cerrado_asigl0 == 'N' && !empty($item->max_puja))
                        <p class="salida">{{ trans($theme . '-app.lot.puja_actual') }}<span>
                                {{ \Tools::moneyFormat($item->max_puja->imp_asigl1) }}
                                {{ trans($theme . '-app.subastas.euros') }}</span></p>
                    @elseif ($item->tipo_sub == 'P' || ($item->tipo_sub == 'O' && $item->cerrado_asigl0 == 'N' && empty($item->max_puja)))
                        <p class="salida">{{ trans($theme . '-app.lot_list.no_bids') }} </p>
                    @elseif ($item->tipo_sub == 'W' && $item->subabierta_sub == 'O' && $item->cerrado_asigl0 == 'N' && $item->open_price != 0)
                        <p class="salida">{{ trans($theme . '-app.lot.puja_actual') }}
                            <span class="{{ $winner }}"> {{ \Tools::moneyFormat($item->open_price) }}
                                {{ trans($theme . '-app.subastas.euros') }}
                            </span>
                        </p>
                    @elseif($item->tipo_sub == 'W' && $item->subabierta_sub == 'O' && $item->cerrado_asigl0 == 'N' && $item->open_price == 0)
                        <p class="salida">{{ trans($theme . '-app.lot_list.no_bids') }} </p>
                    @elseif ($item->tipo_sub == 'W' && $item->subabierta_sub == 'O' && $item->cerrado_asigl0 != 'N')
                        <?php //ponemos el espacio para que no descuadre
                        ?>
                        <p class="salida"></p>
                    @else
                        <p class="salida"></p>
                    @endif


                    @if (\Config::get('app.awarded'))
                        <p class="salida">
                            @if ($item->cerrado_asigl0 == 'D')
                                {{ trans($theme . '-app.subastas.dont_available') }}
                            @elseif(
                                ($item->cerrado_asigl0 == 'S' && $item->remate_asigl0 == 'S' && !empty($precio_venta)) ||
                                    ($item->subc_sub == 'H' && !empty($item->impadj_asigl0)))
                                @if ($item->subc_sub == 'H' && !empty($item->impadj_asigl0))
                                    @php($precio_venta = $item->impadj_asigl0)
                                @endif
                                {{ trans($theme . '-app.subastas.buy_to') }}:
                                <span>{{ \Tools::moneyFormat($precio_venta) }}
                                    {{ trans($theme . '-app.subastas.euros') }}</span>
                            @elseif($item->cerrado_asigl0 == 'S' && (!empty($precio_venta) || $item->desadju_asigl0 == 'S'))
                                {{ trans($theme . '-app.subastas.buy') }}
                            @elseif($item->cerrado_asigl0 == 'S' && empty($precio_venta))
                                {{ trans($theme . '-app.subastas.dont_buy') }}
                            @endif
                        </p>
                    @endif
                    @if (($item->tipo_sub == 'P' || $item->tipo_sub == 'O') && $item->cerrado_asigl0 == 'N')
                        <p class="salida-time">
                            <i class="fa fa-clock-o"></i>
                            <span class="timer" data-countdown="{{ strtotime($item->close_at) - getdate()[0] }}"
                                data-format="<?= \Tools::down_timer($item->close_at) ?>"></span>
                        </p>
                    @elseif($item->tipo_sub == 'P' || $item->tipo_sub == 'O')
                        <p class="salida"></p>
                    @endif
                @else
                    @if ($item->tipo_sub != 'V')

                        @if (\Config::get('app.estimacion'))
                            <p class="salida"><span></span></p>
                        @elseif(\Config::get('app.impsalhces_asigl0'))
                            <p class="salida"><span></span></p>
                        @endif
                    @else
                        <p class="salida"><span></span></p>
                    @endif
                    <?php // si son tipo P o O tienen dos lineas mas, la puja y el reloj
                    ?>
                    @if ($item->tipo_sub == 'P' || $item->tipo_sub == 'O')
                        <p class="salida"></p>
                        <p class="salida"></p>
                    @endif
                    @if (\Config::get('app.awarded'))

                        @if ($item->retirado_asigl0 != 'N')
                            <p class="salida"></p>
                        @elseif($item->fac_hces1 == 'D' || $item->fac_hces1 == 'R')
                            <p class="salida"></p>
                        @endif

                    @endif


                @endif

            </div>

        </div>
    </div>
</div>
