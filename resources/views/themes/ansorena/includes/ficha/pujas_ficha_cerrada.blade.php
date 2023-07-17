@php
    $precio_venta = null;
    if (!empty($lote_actual->himp_csub)) {
        $precio_venta = $lote_actual->himp_csub;
    }
    //si es un histórico y la subasta del asigl0 = a la del hces1 es que no está en otra subasta y podemso coger su valor de compra de implic_hces1
    elseif ($lote_actual->subc_sub == 'H' && $lote_actual->cod_sub == $lote_actual->sub_hces1 && $lote_actual->lic_hces1 == 'S' and $lote_actual->implic_hces1 > 0) {
        $precio_venta = $lote_actual->implic_hces1;
    }

    //Si hay precio de venta y impsalweb_asigl0 contiene valor, mostramos este como precio de venta
    $precio_venta = !empty($precio_venta) && $lote_actual->impsalweb_asigl0 != 0 ? $lote_actual->impsalweb_asigl0 : $precio_venta;
@endphp

<div class="lot-sold">
    <p class="ff-highlight ficha-lot-price mb-3">
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
        @elseif($cerrado && Session::has('user') && empty($precio_venta))
            {{ trans("$theme-app.subastas.dont_buy") }}
        @elseif($devuelto)
            {{ trans("$theme-app.subastas.dont_available") }}
        @endif
    </p>
</div>
