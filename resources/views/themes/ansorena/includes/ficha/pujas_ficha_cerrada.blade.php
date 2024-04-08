@php
	$precio_venta = null;
	if (!empty($lote_actual->himp_csub)) {
	    $precio_venta = $lote_actual->himp_csub;
	}
	//si es un histórico y la subasta del asigl0 = a la del hces1 es que no está en otra subasta y podemso coger su valor de compra de implic_hces1
	elseif (
	    $lote_actual->subc_sub == 'H' &&
	        $lote_actual->cod_sub == $lote_actual->sub_hces1 &&
	        $lote_actual->lic_hces1 == 'S' and
	    $lote_actual->implic_hces1 > 0
	) {
	    $precio_venta = $lote_actual->implic_hces1;
	}

	//Si hay precio de venta y impsalweb_asigl0 contiene valor, mostramos este como precio de venta
	$precio_venta =
	    !empty($precio_venta) && $lote_actual->impsalweb_asigl0 != 0 ? $lote_actual->impsalweb_asigl0 : $precio_venta;

	$not_buyed_with_user_logged = $cerrado && Session::has('user') && empty($precio_venta);
@endphp

<div class="lot-sold">
	<div class="w-100 d-flex align-items-center justify-content-between">
		<div>
			<p class="ff-highlight ficha-lot-price mb-2">
				{{ trans("$theme-app.lot.lot-price") . ' ' . $lote_actual->formatted_impsalhces_asigl0 . ' ' . trans("$theme-app.subastas.euros") }}
			</p>

			<p class="ff-highlight ficha-lot-price">
				{{--   EL USUARIO DEBE ESTAR LOGEADO PARA QUE PUEDA VER EL RESULTADO DE LA SUBASTA --}}
				@if ($cerrado && Session::has('user') && !empty($precio_venta) && $remate)
					{{ trans("$theme-app.subastas.buy_to") . ' ' . Tools::moneyFormat($precio_venta, trans("$theme-app.subastas.euros")) }}

					{{--   EL USUARIO DEBE ESTAR LOGEADO PARA QUE PUEDA VER EL RESULTADO DE LA SUBASTA --}}
				@elseif($cerrado && Session::has('user') && !empty($precio_venta) && !$remate)
					{{ trans("$theme-app.subastas.buy") }}
				@elseif($subasta_venta && !$cerrado && $lote_actual->end_session > time())
					{{ trans("$theme-app.subastas.dont_buy") }}

					{{--   EL USUARIO DEBE ESTAR LOGEADO PARA QUE PUEDA VER EL RESULTADO DE LA SUBASTA --}}
				@elseif($not_buyed_with_user_logged)
					{{ trans("$theme-app.subastas.dont_buy") }}
				@elseif($devuelto)
					{{ trans("$theme-app.subastas.dont_available") }}
				@endif
			</p>
		</div>

		@if ($not_buyed_with_user_logged)
			<a class="btn btn-lb-primary btn-medium"
				href="mailto:{{ Config::get('app.email-buy-lot-not-selled', '') }}?subject={{ trans("$theme-app.lot.mail_not_selled_subject") . $lote_actual->ref_asigl0 }}">
				{{ trans("$theme-app.lot.buy") }}
			</a>
		@endif
	</div>

	@if ($not_buyed_with_user_logged)
		<p class="mt-3 info-message">{{ trans("$theme-app.lot.info_num_tel") }}</p>
	@endif
</div>
