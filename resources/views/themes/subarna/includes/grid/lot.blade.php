<article class="square lot-square" {!! $codeScrollBack !!}>
    <a title="{{ $titulo }}" {!! $url !!}>

        @include('includes.grid.labelLots')

        <div class="item_lot">

            <div class="item_img">
                {{-- <picture>
                    <source
                        srcset="{{ Tools::serverLotUrlImg('www.subarna.net', 500, $item->num_hces1, $item->lin_hces1) }}">
                    <img class="img-responsive" src="{{ $img }}" alt="{{ $titulo }}" loading="lazy">
                </picture> --}}

				<img class="img-responsive" src="{{ $img }}" alt="{{ $titulo }}" loading="lazy">
            </div>

            <div class="data-container">
                <div class="title_item">
                    <h4 class="seo_h4">
                        <span class="bold"> {{ $item->ref_asigl0 }}.</span>
						<span class="ff-highlight bold">
                        	{{ $titulo }}
						</span>
                    </h4>
                </div>

                <div class="data-price">
                    @if (!$retirado && !$devuelto)
                        <p style="visibility: {{ $item->ocultarps_asigl0 != 'S' ? 'visible' : 'hidden' }}">
                            @if ($subasta_venta && $item->cod_sub == 'VDJ')
                                <span class="salida-title">{{ trans($theme . '-app.subastas.net_price') }}: </span>
                            @elseif($subasta_venta)
                                <span class="salida-title">{{ trans($theme . '-app.subastas.price_sale') }}:</span>
                            @else
                                <span class="salida-title">{{ trans($theme . '-app.lot.lot-price') }}:</span>
                            @endif

                            <span class="salida-price">
                                {{ $precio_salida . trans($theme . '-app.subastas.euros') }}
                            </span>
                        </p>


                       {{--  @if (($subasta_online || ($subasta_web && $subasta_abierta_P)) && !$cerrado && $hay_pujas)
                            <p>
                                <span class="salida-title">
                                    {{ trans($theme . '-app.lot.puja_actual') }}
                                </span>
                                <span class="salida-price {{ $winner }}">
                                    {{ $maxPuja . trans($theme . '-app.subastas.euros') }}
                                </span>
                            </p>
                        @elseif ($subasta_online || ($subasta_web && $subasta_abierta_P && !$cerrado))
                            <p>
                                <span class="salida-title">{{ trans($theme . '-app.lot_list.no_bids') }} </span>
                            </p>
                        @endif --}}



                        {{-- @if ($awarded)
                            <p>
                                @if ($devuelto)
                                    <span
                                        class="salida-title notAvailable">{{ trans($theme . '-app.subastas.dont_available') }}</span>
                                @elseif(($cerrado && $remate && !empty($precio_venta)) || ($sub_historica && !empty($item->impadj_asigl0)))
                                    @if ($sub_historica && !empty($item->impadj_asigl0))
                                        @php($precio_venta = $item->impadj_asigl0)@endphp
                                    @endif
                                    <span class="salida-title soldGrid">
                                        {{ trans($theme . '-app.subastas.buy_to') }}
                                    </span>
                                    <span class="salida-price  soldGrid">
                                        {{ $precio_venta . trans($theme . '-app.subastas.euros') }}
                                    </span>
                                @elseif($cerrado && (!empty($precio_venta) || $desadjudicado))
                                    <span class="salida-title soldGrid2">
                                        {{ trans($theme . '-app.subastas.buy') }}
                                    </span>
                                @elseif($cerrado && empty($precio_venta))
                                    <span class="salida-title notSold">
                                        {{ trans($theme . '-app.subastas.dont_buy') }}
                                    </span>
                                @endif
                            </p>
                        @endif --}}


                        @if ($subasta_online && !$cerrado)
                            <p class="salida-time">
                                <span class="timer" data-countdown="{{ strtotime($item->close_at) - getdate()[0] }}"
                                    data-format="{!! \Tools::down_timer($item->close_at) !!}"></span>
                            </p>
                        @endif

                    @endif
                </div>

            </div>

        </div>
    </a>
</article>
