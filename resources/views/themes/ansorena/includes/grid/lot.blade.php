@php
    $showActualBid = ($subasta_online || ($subasta_web && $subasta_abierta_P)) && !$cerrado && $hay_pujas;
    $showResult = $awarded && Session::has('user');
	$showClosedAndNotBuyed = ($cerrado && empty($precio_venta) && !$compra) || (!empty($isLastHistoryAuction) && $isLastHistoryAuction && $cerrado && empty($precio_venta) && $compra);
@endphp

<div class="col">
    <div role="article" class="card lot-card" {!! $codeScrollBack !!}>

        <div id="{{ $item->sub_asigl0 }}-{{ $item->ref_asigl0 }}" style="position: absolute;top:-180px"></div>

        <div class="lot-card-imageblock">
            <a {!! $url !!} title="{{ $titulo }}" class="stretched-link"></a>

            <div class="lot-card-header">
                <span>{{ $titulo }}</span>

                @if ($subasta_online && !$cerrado)
                    <p class="salida-time">
                        <i class="fa fa-clock-o"></i>
                        <span data-countdown="{{ strtotime($item->close_at) - getdate()[0] }}"
                            data-format="{{ Tools::down_timer($item->close_at) }}" class="timer"></span>
                    </p>
                @endif
            </div>

            <img src="{{ $img }}" alt="{{ $titulo }}" class="card-img-top">

        </div>

        <div class="card-body lot-card-body">

            <div class="lot-title max-line-4">{!! $item->descweb_hces1 !!}</div>

            <div class="lot-data mt-4">

                @if (!$retirado && !$devuelto)
                    <div class="lot-prices w-100">

                        <p style="visibility: {{ $item->ocultarps_asigl0 != 'S' ? 'visible' : 'hidden' }}">
                            @if ($subasta_venta)
                                {{ trans("$theme-app.subastas.price_sale") }}
                            @else
                                {{ trans("$theme-app.lot.lot-price") }}
                            @endif
                            {{ $precio_salida }} {{ trans("$theme-app.subastas.euros") }}
                        </p>

                        @if ($showActualBid)
                            <p>
                                {{ trans("$theme-app.lot.puja_actual") }}
                                <span class="{{ $winner }}">{{ $maxPuja }}
                                    {{ trans("$theme-app.subastas.euros") }}</span>
                            </p>
                        @endif

                        @if ($showResult)

                            @if (($cerrado && $remate && !empty($precio_venta)) || ($sub_historica && !empty($item->impadj_asigl0)))
                                @if ($sub_historica && !empty($item->impadj_asigl0))
                                    @php($precio_venta = $item->impadj_asigl0)
                                @endif
                                <p>
                                    {{ trans("$theme-app.subastas.buy_to") }} {{ $precio_venta }}
                                    {{ trans("$theme-app.subastas.euros") }}
                                </p>
                            @elseif(($cerrado && !empty($precio_venta)) || ($sub_historica && !empty($item->impadj_asigl0)))
                                <p class="lb-text-capitalize">{{ trans("$theme-app.subastas.buy") }}
                                </p>
                            @elseif($showClosedAndNotBuyed)
								<div class="d-flex align-items-center justify-content-between">
									<p class="lb-text-capitalize">{{ trans("$theme-app.subastas.dont_buy") }}</p>
								</div>
                            @endif
						@endif

                    </div>

                    @if (!$sub_historica)
                        @if ($cerrado && empty($precio_venta) && $compra)
                            <a {!! $url !!} title="{{ $titulo }}"
                                class="btn btn-outline-lb-primary btn-small">
                                {{ trans("$theme-app.subastas.buy_lot") }}
                            </a>
                        @elseif($subasta_venta && !$cerrado)
                            <a {!! $url !!} title="{{ $titulo }}"
                                class="btn btn-outline-lb-primary btn-small">
                                {{ trans("$theme-app.subastas.buy_lot") }}
                            </a>
                        @elseif(!$cerrado)
                            <a {!! $url !!} title="{{ $titulo }}"
                                class="btn btn-outline-lb-primary btn-small">
                                {{ trans("$theme-app.lot.pujar") }}
                            </a>
                        @endif
                    @endif
                @endif

				@if ($showResult && $showClosedAndNotBuyed && !$devuelto && !$isReauctioned && $compra)
					<p class="text-uppercase"><a class="btn btn-outline-lb-primary btn-xsmall" {!! $url !!}>{{ trans("$theme-app.lot.buy") }}</a></p>
				@endif
            </div>

        </div>

    </div>
</div>
