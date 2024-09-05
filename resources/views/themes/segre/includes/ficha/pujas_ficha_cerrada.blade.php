<?php

$name = '';
$phone = '';
$email = '';
if (!empty($data['usuario'])) {
    $name = $data['usuario']->nom_cliweb;
    $phone = $data['usuario']->tel1_cli;
    $email = $data['usuario']->email_cliweb;
}

$precio_venta = null;
if (!empty($lote_actual->himp_csub)) {
    $precio_venta = $lote_actual->himp_csub;
}
//si es un histórico y la subasta del asigl0 = a la del hces1 es que no está en otra subasta y podemso coger su valor de compra de implic_hces1
else {
    $precio_venta = $lote_actual->implic_hces1;
}

//Si hay precio de venta y impsalweb_asigl0 contiene valor, mostramos este como precio de venta
$precio_venta = !empty($precio_venta) && $lote_actual->impsalweb_asigl0 != 0 ? $lote_actual->impsalweb_asigl0 : $precio_venta;
?>
<div class="info_single lot-sold col-xs-12 no-padding">

    <div class="col-xs-8 col-sm-12 no-padding ">
        @if ($cerrado && !empty($precio_venta) && $remate)
            <div class="pre">
                <p class="pre-title-principal adj-text">{{ trans($theme . '-app.subastas.buy_to') }}</p>
                <p class="pre-price">{{ \Tools::moneyFormat($precio_venta) }} {{ trans($theme . '-app.subastas.euros') }}
                </p>
            </div>
        @elseif($cerrado && !empty($precio_venta) && !$remate)
            <div class="pre">
                <p class="pre-title-principal adj-text">{{ trans($theme . '-app.subastas.buy') }}</p>
            </div>
        @elseif($devuelto)
            <div class="pre">
                <p class="pre-title-principal adj-text">{{ trans($theme . '-app.subastas.dont_available') }}</p>
            </div>
        @elseif($retirado)
            <div class="pre">
                <p class="pre-title-principal adj-text">{{ trans($theme . '-app.subastas.dont_available') }}</p>
            </div>
        @else
            {{-- if(!$sub_historica && !$sub_cerrada ) --}}
            {{-- Formulario de  petición de información --}}
            @if (!empty($lote_actual->impsalhces_asigl0))
                <div class="pre lot-sold_impsal">
                    <p class="pre-title-principal adj-text">{{ trans($theme . '-app.lot.lot-price') }}</p>
                    <p class="pre-price">{{ \Tools::moneyFormat($lote_actual->impsalhces_asigl0) }}
                        {{ trans($theme . '-app.subastas.euros') }}</p>
                </div>
            @endif

            <p class="pre-title-principal adj-text"> {{ trans($theme . '-app.galery.request_information') }} </p>
            <form id="infoLotForm" name="infoLotForm" method="post" action="javascript:sendInfoLot()">
				<input type="hidden" data-sitekey="{{ config('app.captcha_v3_public') }}" name="captcha_token" value="">
                <input name="auction" type="hidden"
                    value="{{ $lote_actual->cod_sub }} - {{ $lote_actual->des_sub }}">
                <input name="lot" type="hidden" value="   {{ $lote_actual->descweb_hces1 }} ">

                <div class="form-group">
                    <div class="input-effect col-xs-12">
                        <label>{{ trans($theme . '-app.login_register.contact') }}</label>
                        <input class="form-control  " id="texto__1__nombre" name="nombre" data-placement="right"
                            data-content="" type="text" value="{{ $name }}" onblur="comprueba_campo(this)"
                            placeholder="" autocomplete="off">

                    </div>

                    <div class="input-effect col-xs-12">
                        <label>{{ trans($theme . '-app.foot.newsletter_text_input') }}</label>
                        <input class="form-control  " id="email__1__email" name="email" data-placement="right"
                            data-content="" type="text" value="{{ $email }}" onblur="comprueba_campo(this)"
                            placeholder="" autocomplete="off">

                    </div>

                    <div class="input-effect col-xs-12">
                        <label>{{ trans($theme . '-app.user_panel.phone') }}</label>
                        <input class="form-control  " id="texto__1__telefono" name="telefono" data-placement="right"
                            data-content="" type="text" value="{{ $phone }}" onblur="comprueba_campo(this)"
                            placeholder="" autocomplete="off">

                    </div>

                    <div class="input-effect col-xs-12">
                        <label>{{ trans($theme . '-app.global.coment') }}</label>
                        <textarea class="form-control  " id="textogrande__0__comentario" name="comentario" rows="10">  </textarea>

                    </div>
                    <div class="col-xs-12 mt-3">
                        <p class="captcha-terms">
                            {!! trans("$theme-app.global.captcha-terms") !!}
                        </p>
                    </div>

                    <div class="col-xs-12 mt-3 mb-3">
                        <div class="row">
                            <div class="col-xs-6">
                                <a class="btn button-principal submitButton"
                                    onclick="javascript:submit_form(document.getElementById('infoLotForm'),0);">Enviar</a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

        @endif

    </div>

</div>

<script>
    $(function() {
        $("#RequestInformationView").on("click", function() {
            //	if($("#biographyArtistText").hasClass("hidden"){
            $("#formRequest").toggleClass("hidden");
            $("#desplegableOFF").toggleClass("hidden");
            $("#desplegableON").toggleClass("hidden");
            //}
        })
    });


</script>
