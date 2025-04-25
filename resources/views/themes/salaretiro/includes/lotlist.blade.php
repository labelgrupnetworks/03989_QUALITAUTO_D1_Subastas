<div class="{{ $class_square }} square">
    <div class="item_lot">
        <div class="border_img_lot">
            <a title="{{ $title }}" <?= $url ?>>
                <div class="img_lot">
                    <img class="img-responsive lazy"
                        data-src="/img/thumbs/260/{{ \Config::get('app.emp') }}/{{ $item->num_hces1 }}/{{ $item->imagen }}">

                </div>

                @if (($item->cerrado_asigl0 == 'S' && $item->lic_hces1 == 'S' && $item->subc_sub == 'H') ||
                    $item->retirado_asigl0 != 'N' ||
                    ($item->fac_hces1 == 'D' || $item->fac_hces1 == 'R') ||
                    (\Config::get('app.awarded') && $item->cerrado_asigl0 == 'S' && !empty($precio_venta)))
                    <div class="no_dispo-band">
                        <div class="no_dispo"></div>
                        <p>{{ trans($theme . '-app.subastas.dont_available') }}</p>
                    </div>
                @endif
            </a>
        </div>
        <div class="data-container">

            @if (!empty($titulo))
                <div class="title_lot">
                    <a title="" <?= $url ?>>
                        <div class="control-title">
                            <h4><?= $titulo ?></h4>
                        </div>
                    </a>
            @endif
        </div>
        <div class="data-price">

            @if ($item->tipo_sub != 'V')
                @if ($item->formatted_impsalhces_asigl0 == '0' && $item->cerrado_asigl0 == 'N' && $item->tipo_sub == 'W')
                    <p class="salida">{{ trans($theme . '-app.lot.lot-price-consult') }}</p>
                @elseif(\Config::get('app.estimacion'))
                    <p class="salida">{{ trans($theme . '-app.lot.estimate') }} <span>
                            {{ $item->formatted_imptas_asigl0 }} - {{ $item->formatted_imptash_asigl0 }}
                            {{ trans($theme . '-app.subastas.euros') }}</span></p>
                @elseif(\Config::get('app.impsalhces_asigl0'))
                    <p class="salida" style="visibility: {{ $item->ocultarps_asigl0 != 'S' ? 'visible' : 'hidden' }}">
                        {{ trans($theme . '-app.lot.lot-price') }}
                        <span>
                            {{ $item->formatted_impsalhces_asigl0 }}
                            {{ trans($theme . '-app.subastas.euros') }}
                        </span>
                    </p>
                @endif
			@else
				@php
				$pvp = Tools::moneyFormat($item->impsalhces_asigl0 + $item->impsalhces_asigl0 * ($item->comlhces_asigl0 / 100) * 1.21, trans("$theme-app.subastas.euros"), 2);
				@endphp
				<p class="salida">PVP:<span> {{ $pvp }}</span></p>
            @endif

            @if (($item->tipo_sub == 'P' || $item->tipo_sub == 'O') && $item->cerrado_asigl0 == 'N' && !empty($item->max_puja))
                <p class="salida">{{ trans($theme . '-app.lot.puja_actual') }}
                    <span>
                        {{ \Tools::moneyFormat($item->max_puja->imp_asigl1) }}
                        {{ trans($theme . '-app.subastas.euros') }}
                    </span>
                </p>
            @elseif (($item->tipo_sub == 'P' || $item->tipo_sub == 'O') && $item->cerrado_asigl0 == 'N' && empty($item->max_puja))
                <p class="salida"></p>
            @elseif ($item->tipo_sub == 'W' && ($item->subabierta_sub == 'S' || $item->subabierta_sub == 'O') && $item->cerrado_asigl0 == 'N' && $item->open_price >= $item->impsalhces_asigl0)
                <p class="salida">{{ trans($theme . '-app.lot.puja_actual') }}
                    <span class="{{ $winner }}"> {{ \Tools::moneyFormat($item->open_price) }}
                        {{ trans($theme . '-app.subastas.euros') }}
                    </span>
                </p>
            @elseif ($item->tipo_sub == 'W' && $item->subabierta_sub == 'S' && $item->cerrado_asigl0 != 'N')
                {{-- ponemos el espacio para que no descuadre --}}
                <p class="salida"></p>
            @endif

            @if (\Config::get('app.awarded') && $item->cerrado_asigl0 != 'N')
                <p class="salida availability">
                    @if ($item->cerrado_asigl0 == 'D')
                        {{ trans($theme . '-app.subastas.dont_available') }}
                    @elseif($item->cerrado_asigl0 == 'S' && !empty($precio_venta) && $item->remate_asigl0 == 'S')
                        {{ trans($theme . '-app.subastas.buy_to') }}:
                        <span class="pill">
							{{ \Tools::moneyFormat($precio_venta) }}
                            {{ trans($theme . '-app.subastas.euros') }}
						</span>
                    @elseif($item->cerrado_asigl0 == 'S' && (!empty($precio_venta) || $item->desadju_asigl0 == 'S'))
                        {{ trans($theme . '-app.subastas.dont_available') }}
                    @elseif(($item->cerrado_asigl0 == 'S' && $item->lic_hces1 == 'S' && $item->subc_sub == 'H') || $item->retirado_asigl0 != 'N' || ($item->fac_hces1 == 'D' || $item->fac_hces1 == 'R') || (\Config::get('app.awarded') && $item->cerrado_asigl0 == 'S' && !empty($precio_venta)))
                        {{ trans($theme . '-app.subastas.dont_available') }}
                    @elseif($item->cerrado_asigl0 == 'S' && empty($precio_venta) && $item->subc_sub == 'H')
                        {{ trans($theme . '-app.subastas.consult_disp') }}
                    @elseif($item->cerrado_asigl0 == 'S' && empty($precio_venta))
                        {{ trans($theme . '-app.lot_list.available') }}
                    @else
                        {{ trans($theme . '-app.lot_list.available') }}
                    @endif
                </p>
            @endif

			@if (($item->tipo_sub == 'P' || $item->tipo_sub == 'O') && $item->cerrado_asigl0 == 'N')
                <p class="salida">
                    <i class="fa fa-clock-o"></i>
                    <span data-countdown="{{ strtotime($item->close_at) - getdate()[0] }}"
                        data-format="<?= \Tools::down_timer($item->close_at) ?>" class="timer">
					</span>
                </p>
            @elseif($item->tipo_sub == 'P' || $item->tipo_sub == 'O')
                <p class="salida"></p>
            @endif

        </div>

    </div>

</div>
</div>
<script>
    //--- Añadimos tres puntitos al final en firefox
    $('.square .title_lot h4 span:last-child').each(function() {
        var alto = parseInt($(this).css('height'))
        if (alto > 28) {
            $(this).addClass('truncat')
        }
    })
    //-----
</script>
