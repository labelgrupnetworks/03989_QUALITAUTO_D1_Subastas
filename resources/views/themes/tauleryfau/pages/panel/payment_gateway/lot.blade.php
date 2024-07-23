@php
    $euroSymbol = trans("$theme-app.lot.eur");
@endphp

<div class="panel-lot-wrapper">
	@if (empty(Config::get('app.pasarela_web')))
	<input class="filled-in add-carrito hidden" name="carrito[{{ $cod_sub }}][{{ $ref }}][pagar]"
		type="checkbox" checked>
	@endif

	<input name="carrito[{{ $cod_sub }}][{{ $ref }}][envios]" type="hidden" value='1'>
	<input name="carrito[{{ $cod_sub }}][{{ $ref }}][exportacion]" type="hidden" value='{{ $pais_clid }}'>

    <div class="panel-lot payment-lot">
        <div class="panel-lot_img">
            <img class="img-responsive" src="{{ $image }}" alt="" loading="lazy">
        </div>
        <div class="panel-lot_ref">
            <p>
                <span class="panel-lot_label">{{ trans("$theme-app.user_panel.lot") }}</span>
                {{ $ref }}
            </p>
        </div>
        <div class="panel-lot_desc">
            <p>{{ strip_tags($description) }}</p>
        </div>
        <div class="panel-lot_label label-price-actual">
            <span>{{ trans("$theme-app.sheet_tr.adjudicate") }}</span>
        </div>
        <div class="panel-lot_actual-price">
            <p>
                {{ Tools::moneyFormat($imp_award, $euroSymbol, 0) }}
            </p>
        </div>
        <div class="panel-lot_label label-price-commission">
            <span>
                {{ trans("$theme-app.user_panel.price_comision") }}
            </span>
        </div>
        <div class="panel-lot_commission-price">
            <p>
				{{ Tools::moneyFormat($imp_commision, $euroSymbol, 2) }}
            </p>
        </div>
        <div class="panel-lot_label label-price-total">
            <span>
                Total
            </span>
        </div>
        <div class="panel-lot_total-price">
            <p>
                {{ Tools::moneyFormat($imp_award + $imp_commision, $euroSymbol, 2) }}
            </p>
        </div>

    </div>
</div>
