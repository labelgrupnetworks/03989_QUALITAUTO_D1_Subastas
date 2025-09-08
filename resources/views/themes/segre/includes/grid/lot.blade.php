@php
    //if title contain <br> or ":" then we need to show only first line
    $referenceTitleArray = explode('|', str_replace(['<br>', ':'], ['|', '|'], $item->descweb_hces1));
    $referenceTitle = $referenceTitleArray[0];
    $titulo = $referenceTitleArray[0];
    if (count($referenceTitleArray) > 1) {
        $titulo = $referenceTitleArray[1];
    }

	$showClosedAndNotBuyed = ($cerrado && empty($precio_venta) && !$compra) || (!empty($isLastHistoryAuction) && $isLastHistoryAuction && $cerrado && empty($precio_venta) && $compra);
@endphp

<div class="card lot-card" {!! $codeScrollBack !!} id="{{$item->cod_sub}}-{{$item->ref_asigl0}}">
    @include('includes.grid.labelLots')

    <a {!! $url !!}>
        <img class="card-img-top" src="{{ $img }}" alt="{{ $titulo }}"
            loading="{{ $loop->iteration > 6 ? 'lazy' : 'auto' }}">
    </a>

    <div class="card-body">
        <p class="card-reference">
            {{ $referenceTitle }}
        </p>
        <h6 class="card-title max-line-1 mb-0">
            {{ $titulo }}
        </h6>

        <p class="card-description max-line-3 opacity-75">
            {{ strip_tags(str_replace('<br>', '. ', $item->desc_hces1)) }}
        </p>

        <div class="lot-prices opacity-75">
            @if (!$retirado && !$devuelto)
                <p class="lot-salida-price">
                    @if (!$subasta_make_offer)
                        @if ($subasta_venta)
                            <span>{{ trans($theme . '-app.subastas.price_sale') }}</span>
                        @else
                            <span>{{ trans($theme . '-app.lot.lot-price') }}</span>
                        @endif

                        <span>{{ $precio_salida }} {{ trans($theme . '-app.subastas.euros') }}</span>
                    @endif
                </p>

                @if (($subasta_online || ($subasta_web && $subasta_abierta_P)) && !$cerrado && $hay_pujas)
                    <p class="lot-actual-bid">
                        <span>{{ trans($theme . '-app.lot.puja_actual') }}</span>
                        <span class="{{ $winner }}">{{ $maxPuja }}
                            {{ trans($theme . '-app.subastas.euros') }}</span>
                    </p>
                @endif
            @endif
        </div>
    </div>

    <div class="card-footer">

        @if (!$devuelto && !$retirado)

            @if ($awarded && !$devuelto && !$retirado)
                @if (($cerrado && $remate && !empty($precio_venta)) || ($sub_historica && !empty($item->impadj_asigl0)))
                    @if ($sub_historica && !empty($item->impadj_asigl0))
                        @php($precio_venta = $item->impadj_asigl0)@endphp
                    @endif

                    <p class="lot-buy-to">
                        <span>{{ trans($theme . '-app.subastas.buy_to') }}</span>
                        <span>{{ $precio_venta }} {{ trans($theme . '-app.subastas.euros') }}</span>
                    </p>
                @elseif($cerrado && empty($precio_venta) && !$compra)
                    <p class="lot-not-buy">
                        <span class="salida-title notSold">
                            {{ trans($theme . '-app.subastas.dont_buy') }}
                        </span>
                    </p>
                @endif
            @endif

            @if ($cerrado && empty($precio_venta) && $compra && (!$sub_historica || $sub_historica && $isLastHistoryAuction))
                <a class="lot-btn lot-btn_buy" {!! $url !!}>
                    {{ trans($theme . '-app.subastas.buy_lot') }}
                </a>
            @elseif($subasta_venta && !$cerrado)
                @if (!$end_session)
                    <a class="lot-btn lot-btn_buy" {!! $url !!}>
                        {{ trans($theme . '-app.subastas.buy_lot') }}
                    </a>
                @endif
            @elseif(!$cerrado && (!$subasta_online || $inicio_pujas_online))
                {{-- Si no está cerrado saldrá el botón o
					si la subasta online está abierta y es superior a la fecha de inicio también aparecerá --}}
                <a class="lot-btn lot-btn_bid" {!! $url !!}>
                    {{ trans($theme . '-app.lot.pujar') }}
                </a>
            @endif
        @endif
    </div>

</div>
